<?
session_start();

require ("../config/config.php");

require ("../auth/tmhOAuth.php");
require ("../auth/tmhUtilities.php");

require ("../classes/Query.class.php");
require ("../classes/Database.class.php");

require ("../classes/textencrypter.class.php");
require ("../classes/JSON.php");//REMOVE

require ("../models/story.class.php");
require ("../models/writers.class.php");
require ("../models/transaction.class.php");
require ("../models/inputValidation.class.php");



class Controller {

	public function __construct($type, $hasAccess, $text, $extra, $savedExtra) {
			
		$db = new Database();
		$db->connect();
					
		$this -> encrypter = new textEncrypter();
		$this -> st = new story();
		$this -> trans = new transaction();
		$this->input = new inputValidation();
		
		switch ($type) {
			case 'latest' :
				$this -> latest($hasAccess);

				break;
			case 'contribute' :
				$this -> contribute($hasAccess, $text, $extra);
				break;
			
			case 'signin':
				$_SESSION['prev_text']=$text;
				$_SESSION['extra']=$savedExtra;
				echo json_encode(array("status" => "200"));
				break;
			case'shrink':
				$res = $this->st->shrink($text);
				echo $res;
			break; 	
			default :
				echo json_encode(array("status" => "401","msg"=>"Something you did could not be verified. Please sign in with Twitter to try again."));
		}

	}
	
	private function latest($hasAccess) {

		//return all with diff = 1 (leafs)
		$latest = $this -> st -> latest_tweet();

		//
		$l = count($latest) - 1;
		if ($l < 0) {
			$lt = 0;
		} else {
			$lt = rand(0, $l);
		}

//		if ($hasAccess) {
				
			//CREATE KEY FOR POSTING TWEETS
			$key = $this -> encrypter -> encode($latest[$lt]['writer_id'] . "-" . rand(0, 10000));
			$this -> trans -> updateKey($latest[$lt]['writer_id'], $key);
			

			//GET LATEST			
			$hashtag=constant('HASHTAG');
			$hashtag_count = strlen($hashtag);
			$atReply='@'.$latest[$lt]['writer_screen_name'];
			$atReply_count=strlen($atReply);
			$txt = $latest[$lt]['node_text'];

			//escape output
			filter_var($latest[0]['node_text'], FILTER_SANITIZE_STRING);
			$txt = stripslashes($txt);

			//encode output
			$extra = $this -> encrypter -> encode("&parent_id=" . $latest[$lt]['node_id'] . "&parent_writer_id=" . $latest[$lt]['writer_id'] . "&parent_screen_name=" . $latest[$lt]['writer_screen_name'] . "&key=" . $key."&in_reply_to=".$latest[$lt]['in_reply_to']);

			if ($latest) {
				echo json_encode(array("status" => "200", "latest_tweet" => $txt, "extra" => $extra, "count" => $hashtag_count, "atReply"=>$atReply, "hashtag"=>$hashtag ));
			} else {
				echo json_encode(array("status" => "400", "msg"=>"An error occured. Please reload Parlour and try again."));
			}
		
/*} else {
			echo json_encode(array("status" => "200", "latest_tweet" => $latest[$lt]['node_text'], "extra" => '', "count" => 0));
		}
 * */
 
	}

	private function contribute($hasAccess, $text, $extra) {
			$output= json_encode(array("status" => "400", "msg" => "An error occured. Please reload Parlour and try again."));
		
		if (!$hasAccess) {
			echo json_encode(array("status" => "401", "msg" => "Something you did could not be verified. Please sign in with Twitter to try again."));
			return false;
			
		} else {
			
			//Decode extra params from POST
			if ($extra != '' && $this -> encrypter -> decode($extra) != '') {
				$t = $this -> encrypter -> decode($extra);
				$tmp = explode("&", $t);
				for ($i = 0; $i < count($tmp); $i++) {
					$tmp2 = explode("=", $tmp[$i]);
					${$tmp2[0]} = @$tmp2[1];
				}
			}

			//Perform extra checks on input
			$rtn = $this->input->format($text,$parent_screen_name);
			
			
			if($rtn['is_empty']){
				echo json_encode(array("status" => "400", "empty" => true, "bounds" => false, "msg"=>"Sorry you sent something we could not understand. Please format it and try again."));
				return false;
			}
			if(!$rtn['within_bounds']){
				echo json_encode(array("status" => "400", "empty" => false, "bounds" => true, "msg" => "Sorry you sent something we could not understand. Please format it and try again."));
				return false;
			}
			
			
			/* CHECK FOR UN-AUTH ENTRY */
			$w = new writers();
			$w_id = $w -> writerId($_SESSION['access_token']['user_id']);
			$writer_id = $w_id[0]['writer_id'];

			$req_key = $this -> trans -> hasKey($writer_id);
			$unauth_entry = 0;

			if (!$req_key) {
				$unauth_entry = 1;
			} else {
				if ($key != $req_key[0]['token']) {
					$unauth_entry = 1;
				}
			}

			//Get CHILD ID
			$child_id = $this -> st -> insert_tweet($writer_id, $rtn['text'], $unauth_entry);
			if ($child_id) {
				
				//Insert in hierarchy
				$h_id = $this -> st -> insert_hierarchy($parent_id, $child_id);
				
				if ($h_id) {
					
					
					$autohide=false;
					$msg = $this->st->statusUpdate($rtn['status'],$in_reply_to,$child_id);
					if(!$rtn['hasAt']){
						$msg="Your Tweet was published!";
						$autohide=true;
					}
					$output=json_encode(array("status" => "200", "msg"=>$msg, "autohide"=>$autohide));
					
				}
				
			} 
			
			echo $output;
		}
	}

}



$type = @$_POST['req'];
$text = @$_POST['tweet'];
$extra = @$_POST['extra'];
$savedExtra = @$_POST['savedExtra'];

$hasAccess = false;
if (isset($_SESSION['access_token'])) {
	$hasAccess = true;
}

$controller = new Controller($type, $hasAccess, $text, $extra, $savedExtra);
?>