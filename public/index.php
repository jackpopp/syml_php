<?php

	include('autoload.php');

	$routes = include(__DIR__.'/../app/config/routes.php');

	$request = new Syml\Request();
	$router = new Syml\Router($routes);

	$syml = new Syml\Syml($request, $router);
	echo $syml->run();