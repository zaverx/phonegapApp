<?
header('Content-Type: text/json; charset=utf-8');
include '../classes/simple_html_dom.php';
include '../models/requestNews.php';


class Controller {

	public function __construct() {
		
   
		$news = new requestNews();
		$results = $news->makeRequest();
		$fp = fopen('../../json/results.json', 'w');
		fwrite($fp, json_encode($results));
		fclose($fp);
    	
	}
	function escapeJsonString($value) {
		 # list from www.json.org: (\b backspace, \f formfeed)    
		$escapers =     array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
  }
}

$controller = new Controller();