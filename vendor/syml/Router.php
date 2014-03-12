<?php namespace Syml;

class Router
{

	private $gets = [];
	private $posts = [];
	private $deletes = [];
	private $puts = [];
	private $patches = [];

	public function __construct()
	{

	}	

	public function get($route, $destination)
	{
		$this->gets[$route] = array('route' => $route, 'destination' => $destination);
	}

	public function post($route, $destination)
	{
		$this->posts[$route] = array('route' => $route, 'destination' => $destination);
	}

	public function matchRoute($requestURI, $requestMethod)
	{	
		switch ($requestMethod) {
			case 'GET':
				if (array_key_exists($requestURI, $this->gets))
					return $this->gets[$requestURI];
				break;
			case 'POST':
				if (array_key_exists($requestURI, $this->posts))
					return $this->posts[$requestURI];
				break;
		
			default:
				throw new \Exception("No route found");
				break;
		}
		throw new \Exception("No route found");
	}
}