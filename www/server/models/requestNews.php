<?php
//include '../classes/simple_html_dom.php';
class requestNews{
	
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
    
        $data = $this->parseCurlData($res);
      //  $this->writeToFile($data);
        
        return $data; 
    } 
    /*
     *    PARSE CURL DATA RECEIVED FROM REQUEST
     */
    function parseCurlData($res){
        
        $results = array();
        
        foreach($res as $key=>$val){
            
            $doc = new SimpleXmlElement($val, LIBXML_NOCDATA);
            
            if(isset($doc->channel)){
               $results[$key] = $this->parseRSS($doc,$key);
            }
            if(isset($doc->entry)){
              // return $this->parseAtom($doc);
            }
            
        }
        return $results;
    }

	function parseRSS($xml,$key){
		
	    $cnt = count($xml->channel->item);
		$itemArray = array();
		
	    for($i=0; $i<$cnt; $i++){
            
	    	$channelTitle = $xml->channel->title;
            $channelLink = $xml->channel->link;
            $channelImage = $xml->channel->image->url;                  
            
			$title = $xml->channel->item[$i]->title;
			$desc  = $xml->channel->item[$i]->description;
            
            switch ($key)
            {
            case "newsbeast.gr":
              $hero  = "http://www.newsbeast.gr/".$this->extractImageElem($desc);
              $url   = $xml->channel->item[$i]->guid;
              $content='';
              break;
            case "protothema.gr":
              $hero  = $xml->channel->item[$i]->image;
              $url   = $xml->channel->item[$i]->guid;
              $content='';
              break;
            case "real.gr":
              $url   = $xml->channel->item[$i]->guid;
              $tempUrl=explode("real.gr/",$url);
              $url=$tempUrl[0]."real.gr/DefaultArthro.aspx".$tempUrl[1];
              $hero  = $this->extractImageElem($desc);
              $content='';
              break;  
             case "info-war.gr":
                
                $content =$xml->channel->item[$i]->children("content", true);
                $url   = $xml->channel->item[$i]->guid;
                $hero  = $this->extractImageElem($content);  
              break;
             case "metrogreece.gr":
                
                $content='';
                $url   = $xml->channel->item[$i]->link;
                $hero  = "http://www.metrogreece.gr/".$this->extractImageElem($desc);  
              break;   
              
              case "lifo.gr":
                
                $content='';
                $url   = $xml->channel->item[$i]->link;
                $hero  = "http://www.lifo.gr/".$this->extractImageElem($desc);  
              break;   
                
                
              case "newsbomb.gr":
               
                $content=$desc;
                $url   = $xml->channel->item[$i]->guid;
                $hero  = $this->extractImageElem($desc);  
                
                
              break;   
                
                
                
            default:
              $url   = $xml->channel->item[$i]->guid;
              $hero  = $this->extractImageElem($desc);    
              $content='';
            }
			
			$itemArray[$i] = array("itemId"=>$key."_item_".$i, "channelTitle"=>"$channelTitle", "channelLink"=>"$channelLink", "channelImage"=>"$channelImage", "url"=>"$url", "title"=>"$title", "desc"=>"$desc","hero"=>"$hero","content"=>"$content");
			
	    }
		
		return $itemArray;
	}
	
    /*
     * WRITE JSON OUTPUT TP .JSON FILE
     */
    
    private function writeToFile($data){
        
        $fp = fopen('../../json/results.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
        
    }
    
	private function extractImageElem($txt){
		
		$img = $this->html->load($txt)->find('img');
		
		return $img[0]->src;
		
	}
	
	function getContent($url,$key){
		
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);  
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
		$str = curl_exec($curl);  
		curl_close($curl);  
        
        $result = $this->parseArticle($key,$str);
	
		return $this->parseArticle($key,$str);
	}
    
    private function parseArticle($key,$str){
        
         $result = array();
         switch ($key){
            case "skai.gr":
                $content = str_get_html($str);
                $plainText= $content->find('article',0);
                $mainImage = $content->find('figure',0)->find('img',0)->src;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
              break;
            case "real.gr":
                $content = str_get_html($str);
                $plainText = $content->find("#intext_content_tag",0)->find('.article_pure_text',1);
                $mainImage = $content->find("#intext_content_tag",0)->find('img',0)->src;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
             
              break;
             
            case "newsbeast.gr":
                $content = str_get_html($str);
                $plainText = $content->find("#intext_content_tag",0);
                $mainImage = "http://www.newsbeast.gr/".$content->find(".article",0)->find(".article_sidebar",0)->find('img',0)->src;
             
               foreach($plainText->find('img') as $element){
                    $element->src =   "http://www.newsbeast.gr/".$element->src;
                    $element->width =   "100%";
                }
                $plainText = $plainText->innertext;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
             
              break;
             
               
            case "enet.gr":
                $content = str_get_html($str);
                
                $mainImage = $content->find("#post-content",0)->find(".imageItemMEDIUM",0)->find('img',0)->src;
                $txt = $content->find("#post-content",0);
                $txt->find(".imageItemMEDIUM",0)->find('img',0)->outertext = '';
             
                $result=array("content"=>"$txt", "mainImage"=>"$mainImage");
             
              break;
            
             case "protothema.gr":
                $content = str_get_html($str);
                    
                $plainText = $content->find(".article-content",0);
                $plainText->find(".adtext",0)->outertext = '';
                $mainImage = $content->find('a[rel=mainphotos]',0)->find('img',0)->src;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
             
              break;
             
              case "tovima.gr":
                $content = str_get_html($str);
             
                $plainText = $content->find(".article_text",0)->innertext;
                $mainImage = $content->find('.article_photo',0)->find('img',0)->src;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
             
              break;
              
              case "rizospastis.gr":
                $content = str_get_html($str);
             
                $plainText = $content->find(".story_body",0);
                $plainText->find(".image",0)->outertext = '';
             
                $mainImage = "http://www.rizospastis.gr/wwwengine/".$content->find(".story_body",0)->find('.image',0)->find('img',0)->src;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
             
              break;
             
              case "metrogreece.gr":
                $content = str_get_html($str);
             
                $plainText = $content->find(".detail_standard_article_text",0)->innertext;
                $mainImage = "http://www.metrogreece.gr/".$content->find("#gallery-wrapper",0)->find('img',0)->src;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
             
               break;
               
               case "lifo.gr":
                $content = str_get_html($str);
             
                $plainText = $content->find("#maintext",0);
                $plainText->find(".imgWrap",0)->outertext = '';
             
                $mainImage = "http://www.lifo.gr/".$content->find(".imgWrap",0)->find('img',0)->src;
                $result=array("content"=>"$plainText", "mainImage"=>"$mainImage");
             
               break;
                 
            default:
              $result = array();
        }
        
        return $result;
    }

}