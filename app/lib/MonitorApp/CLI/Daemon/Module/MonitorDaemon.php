<?php
namespace CLI\Daemon\Module;

use Core\Libraries;

use Model\Database\SQL\LockTable;

use Model\Database\Model\TableReference;

use CLI\Daemon\Module\Interfaces\IModuleJob;
use Monitor\DB;

class MonitorDaemon extends ModuleBase implements IModuleJob {
	const CHECK_EVERY = 600;
	private $actions = array();
	
	function __construct(){
		$this->autoRestart = false;
		
		$this->actions = array();
		foreach(Libraries::getNSExpression(Libraries::getProjectSpace('Actions\\*')) as $class){
			if(class_exists($class))
				$this->actions[] = new $class();
		}
	}
	
	protected function action($action,$parameters){
		foreach($this->actions as $obj){
			call_user_func_array(array($obj,$action), $parameters);
		}
	}
	
	function Loop($parameters){
		$start = time();
		
		foreach(DB\Check::getAll() as $check){
			$known_status = array(array(),array());
			$unknown_status = array();
			foreach($check->getHosts() as $host){
				$checker = $host->getChecker();
				$address = $host->getAddress();
				$status = $host->Process($checker->Check());
				
				switch($status){
					case true:
					case false:
						$this->action('onResult',array($host,$status));
						
						//known status
						$known_status[$status] = $host;						
						break;
						
					case null:
						$this->action('onUnknown',array($host));
						
						//unknown status
						$unknown_status[] = $host;
						break;
				}
			}
			
			$this->action('onCompleteCheck',array($check,$known_status,$unknown_status));
		}
		
		$now = time();
		$time_diff = $now - $start;
		
		if($time_diff < static::CHECK_EVERY){
			Sleep(static::CHECK_EVERY - $time_diff);
		}
	}
}