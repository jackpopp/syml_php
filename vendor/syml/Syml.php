<?php namespace Syml;

class Syml 
{

	private $request;
	private $router;
	
	public function __construct(Request $request, Router $router)
	{
		$this->request = $request;
		$this->router = $router;
		$this->router->setRequestURI($this->request->cleanRequestURI());
	}

	public function run()
	{
		return $this->router->matchRoute();
	}

}