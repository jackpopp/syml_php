<?php namespace Syml;

class Syml 
{

	private $request;
	private $router;

	private $controllerString;
	private $function;
	
	public function __construct(Request $request, Router $router)
	{
		$this->request = $request;
		$this->router = $router;
	}

	public function setController($controller)
	{
		$this->controller = $controller;
		return $this;
	}

	public function getController()
	{
		return $this->controller;
	}

	public function setControllerString($controllerString)
	{
		$this->controllerString = $controllerString;
		return $this;
	}

	public function getControllerString()
	{
		return $this->controllerString;
	}

	public function setFunction($function)
	{
		$this->function = $function;
		return $this;
	}

	public function getFunction()
	{
		return $this->function;
	}

	public function run()
	{
		$this->router->matchRoute($this->request->getRequestURI(), $this->request->getRequestMethod());

		// if the destination is callabe (ie an anonymous function then call it and pass any args we have)
		// otherwise try and constuct the controller and call the function from the desination string
		if (is_callable($this->router->getRoute()['destination']))
		{
 			return call_user_func_array($this->router->getRoute()['destination'], $this->router->getArgs());
		}
		else 
		{
			$dest = explode('#', $this->router->getRoute()['destination']);
			$this->setControllerString(ucfirst($dest[0]).'Controller')->setFunction($dest[1]);
			$controllerString = $this->getControllerString();

			# check for controller directory
			# check for model directory
			# look for controller
			# exectue controller function

			require_once(__DIR__.'/../../app/controllers/'.$controllerString.'.php');
			if ( ! class_exists($controllerString))
				throw new \Exception("Not Controller Found");
				
			$this->setController(new $controllerString());

			return call_user_func_array(array($this->getController(), $this->getFunction()), $this->router->getArgs());
		}

	}

}