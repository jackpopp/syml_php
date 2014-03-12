<?php namespace Syml;

class Router
{

	private $requestURI;
	private $routes;

	public function __construct($routes)
	{
		$this->routes = $routes;
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

	public function getRoutes(){
		return $this->routes;
	}

	public function matchRoute()
	{
		$routes = $this->getRoutes();

		if (array_key_exists($this->getRequestURI(), $routes))
			return $routes[$this->getRequestURI()];

		throw new \Exception("No route found");
		
	}
}