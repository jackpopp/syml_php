<?php namespace Syml;

class Router
{

	private $requestURI;
	private $requestType;

	private $gets = [];
	private $posts = [];
	private $deletes = [];
	private $puts = [];
	private $patches = [];

	public function __construct()
	{
		//$this->routes = $routes;
		$this->setRequestType();
	}	

	public function getRequestURI()
	{
		return $this->requestURI;
	}

	public function setRequestURI($requestURI)
	{
		$this->requestURI = $requestURI;
		return $this;
	}

	public function setRequestType()
	{
		$this->requestType = $_SERVER['REQUEST_METHOD'];
	}

	public function getRequestType()
	{
		return $this->requestType;
	}

	public function get($route, $destination)
	{
		$this->gets[$route] = array('route' => $route, 'destination' => $destination);
	}

	public function post($route, $destination)
	{
		$this->posts[$route] = array('route' => $route, 'destination' => $destination);
	}

	public function matchRoute()
	{	
		switch ($this->getRequestType()) {
			case 'GET':
				if (array_key_exists($this->getRequestURI(), $this->gets))
					return $this->gets[$this->getRequestURI()];
				break;
			case 'POST':
				if (array_key_exists($this->getRequestURI(), $this->posts))
					return $this->posts[$this->getRequestURI()];
				break;
		
			default:
				throw new \Exception("No route found");
				break;
		}
		throw new \Exception("No route found");
	}
}