<?php
header('Content-Type: text/html; charset=utf-8');
include '../classes/simple_html_dom.php';
class requestNews{
	/* $feed_array= array(
         "http://feeds.feedburner.com/skai/Uulu?format=xml",
         "http://www.real.gr/Rss.aspx?pid=143",
         "http://www.newsbeast.gr/feeds/home",
         "http://ws.kathimerini.gr/xml_files/news.xml",
         "http://www.enet.gr/rss?i=news.el.article",
         "http://www.protothema.gr/rss/news/general/",
         "http://www.tovima.gr/feed/allnews/",
         "http://www.rizospastis.gr/wwwengine/rssFeed.do?channel=Top",
         
     );*/
	 function __construct(){
	 	$this->html = new simple_html_dom();
	 	
	 }
	 
    
    function multiple_threads_request($nodes){ 
        $mh = curl_multi_init(); 
        $curl_array = array(); 
        foreach($nodes as $i => $url) 
        { 
            $curl_array[$i] = curl_init($url); 
            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true); 
            curl_multi_add_handle($mh, $curl_array[$i]); 
        } 
        $running = NULL; 
        do { 
            
            curl_multi_exec($mh,$running); 
        } while($running > 0); 
        
        $res = array(); 
        foreach($nodes as $i => $url) 
        { 
            $res[$i] = curl_multi_getcontent($curl_array[$i]); 
        } 
        
        foreach($nodes as $i => $url){ 
            curl_multi_remove_handle($mh, $curl_array[$i]); 
        } 
        curl_multi_close($mh);        
        return $res; 
    } 
    
    

	function parseRSS($xml,$key){
		
		
	    $cnt = count($xml->channel->item);
		$itemArray = array();
		
	    for($i=0; $i<$cnt; $i++){
	    	
			$url   = $xml->channel->item[$i]->link;
			$title = $xml->channel->item[$i]->title;
			$desc  = $xml->channel->item[$i]->description;
            
            switch ($key)
            {
            case "newsbeast.gr":
              $hero  = "http://www.newsbeast.gr/".$this->extractImageElem($desc);
              break;
            case "protothema.gr":
              $hero  = $xml->channel->item[$i]->image;
              break;
            default:
              $hero  = $this->extractImageElem($desc);
            }
			
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

$news = new requestNews();

$feed_array= array(
         "skai.gr" =>"http://feeds.feedburner.com/skai/Uulu?format=xml",
         "real.gr"=>"http://www.real.gr/Rss.aspx?pid=143",
         "newsbeast.gr" =>"http://www.newsbeast.gr/feeds/home",
         "kathimerini.gr" =>"http://ws.kathimerini.gr/xml_files/enews.xml",
         "enet.gr" =>"http://www.enet.gr/rss?i=news.el.article",
         "protothema.gr" =>"http://www.protothema.gr/rss/news/general/",
         "tovima.gr" =>"http://www.tovima.gr/feed/allnews/",
         "rizospastis.gr" =>"http://www.rizospastis.gr/wwwengine/rssFeed.do?channel=Top",
         "metrogreece.gr" =>"http://www.metrogreece.gr/Rss/tabid/90/rssid/2/Default.aspx",
         "lifo.gr" =>"http://www.lifo.gr/blogs.rss"
     );
echo "<pre>";
$res = $news->multiple_threads_request($feed_array);
$results = array();


foreach($res as $key=>$val){
    $doc = new SimpleXmlElement($val, LIBXML_NOCDATA);
    $results[$key] = $news->parseRSS($doc,$key);
    
}
$fp = fopen('../../json/results.json', 'w');
fwrite($fp, json_encode($results));
fclose($fp);




print_r($res); 
echo "</pre>";
?>