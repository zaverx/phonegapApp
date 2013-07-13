<?php
class writers extends Database {

	function process($data) {
		$this->connect();
		$res=$this->writerId($data['writer_twitter_id']);

		if ($res) {

			$res = $this -> update('writers', array("revisit =revisit+1"), array("writer_twitter_id = '%s'", $data['writer_twitter_id']));
		
		} else {
			$res = $this -> insert('writers', $data);
			
		}

		
		
		return $res;
	}
	function writerId($writer_twitter_id){
		
		$res = $this -> select('writers', array('writer_id'), array("writer_twitter_id = '%s'", $writer_twitter_id));
		return $res;
		
	}


}
?>