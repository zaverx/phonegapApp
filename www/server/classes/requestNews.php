<?php

include 'simple_html_dom.php';

class requestNews{
	 	
	 function __construct(){
	 	$this->html = new simple_html_dom();
	 	$this->makeRequest();
	 }
	 
	function makeRequest(){
			
		$ch = curl_init("http://feeds.feedburner.com/skai/Uulu?format=xml");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		$data = curl_exec($ch);
		curl_close($ch);
		$this->parseData($data);	
		
	}
	
	function parseData($data){
		
		$doc = new SimpleXmlElement($data, LIBXML_NOCDATA);
	
		if(isset($doc->channel)){
		   $this->parseRSS($doc);
		}
		if(isset($doc->entry)){
		   $this->parseAtom($doc);
		}
	}

	function parseRSS($xml){

	    $cnt = count($xml->channel->item);
		$itemArray = array();
		
	    for($i=0; $i<$cnt; $i++){
	    	
			$url 	= $xml->channel->item[$i]->link;
			$title 	= $xml->channel->item[$i]->title;
			$desc = $xml->channel->item[$i]->description;
			$hero = $this->extractImageElem($desc);
			$itemArray[$i] = array("url"=>$url, "title"=>$title, "desc"=>$desc,"hero"=>"$hero");
			
	    }
		
		$arr = array(
		  'a' => 1,
		  'b' => 2,
		  'c' => 3,
		  'd' => 4,
		  'e' => 5
		);
		echo $arr;
	}
	
	private function extractImageElem($txt){
		
		$img = $this->html->load($txt)->find('img');
		
		return $img[0];
		
	}
	
	function process($data) {

		$res = $this -> select('writers', array('writer_id'), array("writer_twitter_id = '%s'", $data['writer_twitter_id']));

		if ($res) {

			$res = $this -> update('writers', array("revisit =revisit+1"), array("writer_twitter_id = '%s'", $data['writer_twitter_id']));

		} else {
			$res = $this -> insert('writers', $data);
		}

		return $res;
	}

}

$res = new requestNews();