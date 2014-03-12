<?php

	include('autoload.php');

	$request = new Syml\Request();
	$router = new Syml\Router();

	include(__DIR__.'/../app/config/routes.php');

	$syml = new Syml\Syml($request, $router);
	print_r($syml->run());