<?php
namespace Monitor\Actions;

use Monitor\DB\Check;
use Monitor\DB\Host;

interface IMonitorAction {
	function onUnknown(Host $host);
	function onResult(Host $host,$status);
	function onCompleteCheck(Check $check,$known_status,$unknown_status);
}