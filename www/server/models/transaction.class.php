<?php
  class transaction extends Database{
  	
	function updateKey($writer_id,$newKey){
		
		$res = $this -> hasKey($writer_id);

		if ($res) {

			$res = $this -> update('writer_tokens', array("token ='%s'", $newKey), array("writer_id = '%s'", $writer_id));
			
		} else {
			
			$res = $this -> insert('writer_tokens', array("writer_id"=>$writer_id,"token"=>$newKey));
			
		}
		
	}
	 function hasKey($writer_id){
			
		$res = $this -> select('writer_tokens', array('token'), array("writer_id = '%s'", $writer_id));
		
		return $res;	
	}
  }
  //    SELECT child FROM tree WHERE lft <= 23 AND rgt >= 24 ORDER BY lft ASC;
?>