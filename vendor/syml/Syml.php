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
		return $this->router->matchRoute($this->request->getRequestURI(), $this->request->getRequestMethod());
	}

}