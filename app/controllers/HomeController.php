<?php

use Syml\View as View;

class HomeController
{

	protected $view;

	public function __construct(View $view)
	{
		$this->view = $view;
	}

	public function index()
	{
		$data = array('title' => 'This is a title');
		$this->view->render('home/home', $data);
	}

	public function show($title)
	{
		$data = array('title' => $title);
		$this->view->render('home/home_title', $data);
	}

	public function create()
	{
		echo 'create';
	}
}