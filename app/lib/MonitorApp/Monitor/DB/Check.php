<?php
namespace Monitor\DB;

use Web\Template;
use Utility\Net\Mail;
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
	
	function _tests($passed = array(),$failed = array()){
		$ret = array();
		foreach($passed as $p){
			$ret[] = array('name'=>$p->getName(),'host'=>$p,'result'=>true);
		}
		foreach($failed as $p){
			$ret[] = array('name'=>$p->getName(),'host'=>$p,'result'=>false);
		}
		return $ret;
	}
	
	function Email($passed = array(),$failed = array()){
		//Message Build
		$vars = array();
		$vars['failed'] = count($failed);
		$vars['passed'] = count($passed);
		$vars['total'] = $vars['failed'] + $vars['passed'];
		$vars['tests'] = $this->_tests($passed,$failed);
		
		$body = new Template('mail_check',$vars,'Mail');
		
		//Message send
		$message = new Mail\Message();
		if($_->vars['failed']){
			$message->setSubject($_->vars['failed'].'/'.$_->vars['total'].' tests failed');
		}else{
			$message->setSubject('All tests failed');
		}
		$message->Send($body);
	}
}