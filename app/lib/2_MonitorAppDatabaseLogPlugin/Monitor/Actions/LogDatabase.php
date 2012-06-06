<?php
namespace Monitor\Actions;

use Model\Database\SQL\LockTable;
use Model\Database\Model\TableReference;
use Monitor\DB\Check;
use Monitor\DB\Host;

class LogDatabase implements IMonitorAction {
	protected $logTable;
	
	function __construct(){
		$this->logTable = TableReference::getByTableClass('Log');
	}
	function onUnknown(Host $host){
		
	}
	function onResult(Host $host,$status){
		$text_status = ($status?'up':'down');
		
		//Insert / Update Log
		$sql = new LockTable(array('log'=>'write','check'=>'read','host'=>'read'));
		
		$sql = $this->logTable->select('log_id,log_status,log_to')
				->where(array('host_id'=>$host->getId()))
				->order_by('log_to','DESC')
				->limit(1);
		$res = $sql->Execute();
		$row = $res->Fetch();
		
		$insert = array('host'=>$host->getId(),'status'=>$text_status,'to'=>time());
		
		if($res->num_rows){
			$insert['from'] = $row['log_to'];
		}else{
			$insert['from'] = time();
		}
		
		if(!$res->num_rows || $row['log_status'] != $text_status){
			//Insert
			$sql = $this->logTable->fromSQL($insert);
			$sql->Insert();
		}else{
			//Update
			$sql = $this->logTable->update()
						->where('log_id',$row['log_id'])
						->set('log_to',\DB::toTimeStamp(time()));
			$sql->Execute();
		}
		
		$this->logTable->unlock();
	}
	function onCompleteCheck(Check $check,$known_status,$unknown_status){
		
	}
}