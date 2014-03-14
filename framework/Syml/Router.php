<?php namespace Syml;

class Router
{

	private static $methods = array('GET', 'POST', 'DELETE', 'PUT', 'PATCH');

	private $gets = array();
	private $posts = array();
	private $deletes = array();
	private $puts = array();
	private $patches = array();

	private $args = array();
	private $route = null;

	public function __construct()
	{

	}

	public function getMethods()
	{
		return self::$methods;
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
		$methodArrayValue = strtolower($requestMethod).'s';

		if (in_array($requestMethod, $this->getMethods()))
			$foundRoute = $this->resolveRouteFromRoutesArray($requestURI, $this->$methodArrayValue);

		if ($foundRoute == null)
			throw new \Exception("No route found");

		$this->setRoute($foundRoute);
	}

	private function resolveRouteFromRoutesArray($requestURI, $routes)
	{
		# if request uri is empty look to see if a route for forward slash is registered
		if (empty($requestURI) && (array_key_exists('/', $routes)))
			return $routes['/'];

		if (array_key_exists($requestURI, $routes))
			return $routes[$requestURI];

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
		return ($routeKey) ? $routes[$routeKey] : $routeKey;
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