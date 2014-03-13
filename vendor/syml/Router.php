<?php namespace Syml;

class Router
{

	private $gets = [];
	private $posts = [];
	private $deletes = [];
	private $puts = [];
	private $patches = [];

	private $args = [];
	private $route = null;

	public function __construct()
	{

	}

	public function setRoute($route)
	{
		$this->route = $route;
		return $this;
	}	

	public function getRoute()
	{
		return $this->route;
	}

	public function setArgs($args)
	{
		$this->args = $args;
		return $this;
	}

	public function getArgs()
	{
		return $this->args;
	}

	public function addArg($arg)
	{
		$args = $this->getArgs();
		$args[] = $arg;
		$this->setArgs($args);
		return $this;
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
		$foundRoute = null;
		switch ($requestMethod) {
			case 'GET':
				$foundRoute = $this->resolveRouteFromRoutesArray($requestURI, $this->gets);
				break;
			case 'POST':
				$foundRoute = $this->resolveRouteFromRoutesArray($requestURI, $this->posts);
				break;
		
			default:
				throw new \Exception("No route found");
				break;
		}

		if ($foundRoute == null)
			throw new \Exception("No route found");

		$this->setRoute($foundRoute);
	}

	private function resolveRouteFromRoutesArray($requestURI, $routes)
	{
		if (array_key_exists($requestURI, $routes))
			return $routes[$requestURI];

		# pie/{value}
		# pie/5

		$routeKey = null;

		foreach ($routes as $key => $routeObject) {

			$route = $routeObject['route'];
			$route = str_replace("/", "\\", $route);
			$route = preg_replace('/{.*?}/', '/\w+', $route);


			if (preg_match('/('.$route.'$)/', $requestURI))
			{
				$routeKey = $key;
				$this->resolveArgsFromRoute($requestURI, $key);
				break;
			}
		}
		return ($routeKey) ? $routes[$routeKey] : null;
	}

	private function resolveArgsFromRoute($requestURI, $route)
	{
		$routeStrings = explode('/', $route);
		$reqeustStrings = explode('/', $requestURI);

		foreach ($routeStrings as $key => $string) {
			if (preg_match('/{.*?}/', $string))
				$this->addArg($reqeustStrings[$key]);
		}
	}
}