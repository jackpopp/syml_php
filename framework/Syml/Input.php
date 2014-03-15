<?php namespace Syml;

class Input 
{

	public function give($key)
	{
		$values = array_merge($_GET, $_POST);

		if (array_key_exists($key, $values))
			return $values[$key];

		return null;
	}

	public function get()
	{
		return $_GET;
	}

	public function post()
	{
		return $_POST;
	}

	public function all()
	{
		return array_merge($_GET, $_POST);
	}
}