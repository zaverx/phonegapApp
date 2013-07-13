<?php
class story extends Database {
	
	function __construct(){
	
		$this->storiesInfo=array(
			"parlourstory1"=>array("oauth_token"=>"token1","oauth_token_secret"=>"secret1"),
			"parlourstory2"=>array("oauth_token"=>"token2","oauth_token_secret"=>"secret2"),
			"parlourstory3"=>array("oauth_token"=>"token3","oauth_token_secret"=>"secret3")
		);
		
	}
	function latest_tweet() {
		//$this->update_hierarchy(1, 1);
		//SELECT ni.node_id,ni.writer_id,ni.node_text,w.writer_screen_name FROM node_info ni, node_relations nr, writers w WHERE ni.writer_id = w.writer_id and ni.node_id=nr.child_node_id and nr.lr_diff=1
		$where = array("ni.writer_id = w.writer_id and ni.node_id=nr.child_node_id and nr.lr_diff=1");
		$res = $this -> select('node_info ni, node_relations nr, writers w', array("ni.node_id", "ni.writer_id", "ni.node_text", "w.writer_screen_name", "ni.in_reply_to"), $where);

		return $res;
	}

	function insert_tweet($writer_id, $text, $unauth_entry) {

		$res = $this -> insert('node_info', array("writer_id" => $writer_id, "node_text" => $text, "unauthorised_entry" => $unauth_entry));
		return $res;
	}

	function insert_hierarchy($parent_id, $child_id) {

		$res = $this -> insert('node_relations', array("parent_node_id" => $parent_id, "child_node_id" => $child_id, "lr_diff"=>1));
		

		if($res){
				
			$fields = array("lr_diff =0");
			$where = array("child_node_id = '%s'", $parent_id);
			$up_res = $this -> update('node_relations', $fields, $where);
		}
		
		
		$this->update_hierarchy(1, 1);
		return $res;

	}

	function update_hierarchy($parent, $left) {

		$right = $left + 1;

		$where = array("parent_node_id = '%s'", $parent);
		$res = $this -> select('node_relations', array('child_node_id', 'parent_node_id'), $where);

		
			for ($i = 0; $i < count($res); $i++) {

				$right = $this -> update_hierarchy($res[$i]['child_node_id'], $right);
			}
			$diff = $right - $left;
			$fields = array("lft ='%s', rgt ='%s', lr_diff='%s'", $left, $right, $diff);
			$where = array("child_node_id = '%s'", $parent);

			$up_res = $this -> update('node_relations', $fields, $where);



		return $right + 1;

	}
	function statusUpdate($status,$tweet_id,$node_id){
	
		$tmhOAuth = new tmhOAuth(array(
		  'consumer_key'    => constant('EXQUISITESTORY_CONSUMER_KEY'),
  		  'consumer_secret' => constant('EXQUISITESTORY_CONSUMER_SECRET'),
		  'user_token'      => $_SESSION['access_token']['oauth_token'],
		  'user_secret'     => $_SESSION['access_token']['oauth_token_secret']
		));
		
		$temp=$status." #twitterfiction";
		if(strlen($temp)<=140){
			$status=$status." #twitterfiction";
		}
		
		$code = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
		  'status' => $status,
		  'in_reply_to_status_id'=>$tweet_id
		  
		));
		
		if ($code == 200) {
			
			$res= json_decode($tmhOAuth->response['response'],true);
			$fields = array("in_reply_to = '%s'", $res['id_str']);
			$where = array("node_id = '%s'", $node_id);
			$up_res = $this -> update('node_info', $fields, $where);
			
			return "<div id='story'>Your Tweet was published! <a href='https://twitter.com/".$res['user']['screen_name']."/status/".$res['id_str']."' target='_blank'>Follow the narrative →</a></div>";
			return false;
		} else {
		  return false;
		}
		
	}
	
	
	function shrink($text){
		$ch = curl_init();
		$text  = urlencode($text);
		
		curl_setopt($ch, CURLOPT_URL, "http://tweetshrink.com/shrink?text=".$text."&type=json");
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);       
        curl_close($ch);
		
        return $output;
	}
}