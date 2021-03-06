<?php
header('Content-Type: text/json; charset=utf-8');
include '../classes/simple_html_dom.php';
include '../models/requestNews.php';


class Controller {

	public function __construct($flag,$url,$key) {
        
        $this->news = new requestNews();
        switch ($flag)
            {
            case "article":
              $results = $this->news->getContent($url,$key);
              echo json_encode($results);
              break;
            case "protothema.gr":
              
              break;
            default:
            /*
              $url="http://www.real.gr/?page=arthro&id=248704&catID=14";
              $results = $this->news->getContent($url,"real.gr");
            echo "<pre>"; print_r($results); echo"</pre>";
            */
             // echo json_encode($results);
            
             $results = $this->makeRequest();
             echo json_encode($results);
             
             
        }
        
	}
    

    
    
    
    function makeRequest(){
        
        $feed_array= array(
             "info-war.gr"    => "http://info-war.gr/feed/",
             "real.gr"        => "http://www.real.gr/Rss.aspx?pid=143",
             "newsbeast.gr"   => "http://www.newsbeast.gr/feeds/home",
             "kathimerini.gr" => "http://ws.kathimerini.gr/xml_files/enews.xml",
             "enet.gr"        => "http://www.enet.gr/rss?i=news.el.article",
             "protothema.gr"  => "http://www.protothema.gr/rss/news/general/",
             "tovima.gr"      => "http://www.tovima.gr/feed/allnews/",
             "rizospastis.gr" => "http://www.rizospastis.gr/wwwengine/rssFeed.do?channel=Top",
             "metrogreece.gr" => "http://www.metrogreece.gr/Rss/tabid/90/rssid/2/Default.aspx",
             "lifo.gr"        => "http://www.lifo.gr/blogs.rss",
             "skai.gr"        => "http://feeds.feedburner.com/skai/Uulu?format=xml",
             "newsbomb.gr"    => "http://feeds.feedburner.com/Newsbombgr?format=xml"
            
        );
        return  $this->news->multiple_threads_request($feed_array);   
        
    }
}
$flag = @$_POST['flag'];
$lnk = @$_POST['lnk'];
$key = @$_POST['key'];
$controller = new Controller($flag,$lnk,$key);
?>