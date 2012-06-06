<?php
namespace Monitor\Protocol;

use Utility\Net\HTTP\Fetch;
use Utility\Net\URL;

class PING {
	static function Check(URL $url){
		$ip = $url->getIP();
		
		if($ip->Ping()){
			return true;
		}
		return false;
	}
}