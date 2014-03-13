<?php

function __autoload($className) {

	$className = implode('/', explode("\\", $className));
	include(__DIR__.'/../framework/'.$className.'.php');
}