<?php
namespace Monitor\Protocol;

use Utility\Net\HTTP\Fetch;
use Utility\Net\URL;

class HTTP {
	static function Check(URL $url){
		$http = new Fetch($url);
		$http->curl[CURLOPT_FOLLOWLOCATION] = false;
		$http->curl[CURLOPT_TIMEOUT] = 15;
		$response = $http->Get();
		$code = (string)$response->getCode();
		if($code{0} == '2' || $code{0} == '3')
			return true;
		
		return false;
	}
}