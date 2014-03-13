<?php namespace Syml;

class Syml 
{

	private $request;
	private $router;
	
	public function __construct(Request $request, Router $router)
	{
		$this->request = $request;
		$this->router = $router;
	}

	public function run()
	{
		$this->router->matchRoute($this->request->getRequestURI(), $this->request->getRequestMethod());
		echo '<pre>';
		print_r($this->router->getRoute());
		print_r($this->router->getArgs());
	}

}