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

	/**
	* Registers a get route by pushing an array with the route as the key 
	* and route and destination data into the get array
	*
	* @param  string            $route        The route we are registering
	* @param  string|callable   $destination  A string with controller and method to be called or an anonymous function
	* @return object            $this         Returns self
	*/
	public function get($route, $destination)
	{
		$this->gets[$route] = array('route' => $route, 'destination' => $destination);
		return $this;
	}

	/**
	* Registers a post route by pushing an array with the route as the key 
	* and route and destination data into the post array
	*
	* @param  string            $route        The route we are registering
	* @param  string|callable   $destination  A string with controller and method to be called or an anonymous function
	* @return object            $this         Returns self
	*/
	public function post($route, $destination)
	{
		$this->posts[$route] = array('route' => $route, 'destination' => $destination);
		return $this;
	}


	/**
	* Determines which route array to match the request uri against and calls 
	* resolveRouteFromtRoutes array to try and match the route
	*
	* @param  string   $requestURI     The URI that has been requested
	* @param  string   $requestMethod  The request method used
	*/
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

	/**
	* Matches a route against any of the registered routes based on reqeust uri and method
	* First checks if the reqeust uri is blank meaning we are just at the domain and requests the '/' route
	* Second checks if they array key matches a route and returns the matching route and destination
	* Third checks if the route matches a dynamic route and returns it while also resolving the arguements from the dynmaic route
	*
	* @param  string   $requestURI     The URI that has been requested
	* @param  string   $requestMethod  The request method used
	*/
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
			# http://regex101.com/r/zZ5xK0
			$route = preg_replace('/{.*?}/', '/[a-zA-Z%_\-0-9\(\)]+', $route);


			if (preg_match('/('.$route.'$)/', $requestURI))
			{
				$routeKey = $key;
				$this->resolveArgsFromRoute($requestURI, $key);
				break;
			}
		}
		return ($routeKey) ? $routes[$routeKey] : $routeKey;
	}

	/**
	* Resolves the arguments from the reqeust uri against the registered route and adds the
	* arugments to the args array
	*
	* @param  string   $requestURI     The URI that has been requested
	* @param  string   $requestMethod  The request method used
	*/
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