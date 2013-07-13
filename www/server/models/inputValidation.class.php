<?php
class inputValidation {

	public function __construct() {
		$this -> hashtag=constant('HASHTAG');
	}

	public function format($text, $screen_name) {

		$rtn = array();
		$value = explode('@' . $screen_name, $text);
		$text = trim($text);
		$rtn['hasAt'] = true;
		
		
		$status = $text;
		$total_count=strlen($status)+strlen($this->hashtag);
		
		if($total_count<140){
			$status.=$this -> hashtag;
		}
		
		
		if (count($value) == 2) {

			if ($value[0] == '' || $value[1] == '') {
				$text = trim($value[0] . $value[1]);
			}
		}
		else{
			$rtn['hasAt'] = false;
		}
		

		$rtn['text'] = $text;
		$rtn['status'] = $status;
		$rtn['is_empty'] = $this -> isEmpty($text, $screen_name);
		$rtn['within_bounds'] = $this -> withinBounds($status, $screen_name);

		return $rtn;
	}

	private function withinBounds($text, $screen_name) {

		if ($text <= 140) {
			return true;
		}

		return false;

	}

	private function isEmpty($text, $screen_name) {

		if ($text == '' || strlen($text) == 0 || $text == $screen_name) {

			return true;

		}

		return false;
	}

}
?>