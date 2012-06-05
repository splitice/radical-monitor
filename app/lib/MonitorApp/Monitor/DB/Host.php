<?php
namespace Monitor\DB;

use Utility\Net\URL;

use Monitor\Checker;
use Model\Database\Model\Table;

class Host extends Table {
	const TABLE_PREFIX = 'host_';
	const TABLE = 'host';
	
	protected $id;
	protected $name;
	protected $address;
	protected $check;
	
	function Process($pass){
		echo $this->name,": ",($pass?'pass':'fail'),"\r\n";
		return $pass;
	}
	
	function getChecker(){
		return new Checker($this->getAddress());
	}
	
	function getAddress(){
		return URL::fromURL($this->address);
	}
}