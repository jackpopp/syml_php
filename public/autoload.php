<?php


// register autloader
function __autoload($className) {

	$className = implode('/', explode("\\", $className));

	if (file_exists(__DIR__.'/../framework/'.$className.'.php'))
		include(__DIR__.'/../framework/'.$className.'.php');
}

// load in all controllers
foreach (glob(__DIR__."/../app/controllers/*.php") as $filename)
{
    include $filename;
}