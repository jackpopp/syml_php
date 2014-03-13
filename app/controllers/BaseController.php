<?php 
	
use Syml\View as View;

class BaseController 
{

	protected $view;

	public function __construct()
	{
		$this->view = new View();
	}

}