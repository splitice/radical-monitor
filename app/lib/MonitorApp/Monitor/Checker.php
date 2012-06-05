<?php
namespace Monitor;

use Utility\Net\URL;

class Checker {
	/**
	 * @var URL
	 */
	protected $url;
	
	function __construct(URL $url){
		$this->url = $url;
	}
	
	function Check(){
		$class = '\\Monitor\\Protocol\\'.strtoupper($this->url->getScheme());

		if(class_exists($class)){
			return $class::Check($this->url);
		}
	}
}