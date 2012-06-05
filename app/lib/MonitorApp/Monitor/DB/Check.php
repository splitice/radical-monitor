<?php
namespace Monitor\DB;

use Model\Database\Model\Table;

class Check extends Table {
	const TABLE_PREFIX = 'check_';
	const TABLE = 'check';
	
	protected $id;
	protected $name;
	protected $email;
	
	function Alert($passed = array(),$failed = array()){
		echo $this->name,": ",($pass?'pass':'fail'),"\r\n";
	}
}