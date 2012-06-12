<?php
namespace Monitor\Actions;

use Model\Database\Model\TableReference;

use Monitor\DB\Check;
use Monitor\DB\Host;

class EmailUser implements IMonitorAction {
	protected $logTable;
	
	function __construct(){
		$this->logTable = TableReference::getByTableClass('Log');
	}
	function onUnknown(Host $host){
		
	}
	function onResult(Host $host,$status){
		
	}
	function onCompleteCheck(Check $check,$known_status,$unknown_status){
		if($this->_do($known_status[0],false)){
			if($this->_do($known_status[1],true)){
				if($known_status[0] || $known_status[1]){
					$this->_email($check,$known_status,$unknown_status);
				}
			}
		}
	}
	protected function _email(Check $check,$known_status,$unknown_status){
		$check->Email($known_status[1],$known_status[0]);
		exit;
	}
	protected function _do($status,$pass){
		$text_status = $pass?'up':'down';
		
		if(!$status) return true;
		
		foreach($status as $host){
			$sql = $this->logTable->select('log_id,log_status')
				->where(array('host_id'=>$host->getId()))
				->order_by('log_to','DESC')
				->limit(1);
			
			$res = $sql->Execute();
			$row = $res->Fetch();
			
			if(!$res->num_rows || $row['log_status'] != $text_status){
				return true;
			}
		}
		
		return false;
	}
}