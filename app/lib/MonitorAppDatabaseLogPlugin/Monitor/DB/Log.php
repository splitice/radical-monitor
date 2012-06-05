<?php
namespace Monitor\DB;

use Model\Database\DynamicTypes\DateTime;

use Monitor\Checker;
use Model\Database\Model\Table;

class Log extends Table {
	const TABLE_PREFIX = 'log_';
	const TABLE = 'log';
	
	protected $id;
	protected $host;
	/**
	 * Date from
	 * 
	 * @var DateTime
	 */
	protected $from;
	/**
	 * Date to
	 * 
	 * @var DateTime
	 */
	protected $to;
	protected $status;
}