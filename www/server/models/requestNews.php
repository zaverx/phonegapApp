<?php


class requestNews{
	 	
	 function __construct(){
	 	$this->html = new simple_html_dom();
	 	
	 }
	 
	function makeRequest(){
			
		$ch = curl_init("http://feeds.feedburner.com/skai/Uulu?format=xml");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		$data = curl_exec($ch);
		curl_close($ch);

		$doc = new SimpleXmlElement($data, LIBXML_NOCDATA);
	
		if(isset($doc->channel)){
		   return $this->parseRSS($doc);
		}
		if(isset($doc->entry)){
		   return $this->parseAtom($doc);
		}
	}

	function parseRSS($xml){
		
		
	    $cnt = count($xml->channel->item);
		$itemArray = array();
		
	    for($i=0; $i<$cnt; $i++){
	    	
			$url   = $xml->channel->item[$i]->link;
			$title = $xml->channel->item[$i]->title;
			$desc  = $xml->channel->item[$i]->description;
			$hero  = $this->extractImageElem($desc);
			
			$itemArray[$i] = array("url"=>"$url", "title"=>"$title", "desc"=>"$desc","hero"=>"$hero");
			
	    }
		
		return $itemArray;
	}
	
	private function extractImageElem($txt){
		
		$img = $this->html->load($txt)->find('img');
		
		return $img[0]->src;
		
	}
	
	function getContent(){
		
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
		$str = curl_exec($curl);  
		curl_close($curl);  
		$content = str_get_html($str);
		//FOR SKAI	 
		return $content->find('article',0)->plaintext;	
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