<?php namespace Syml;

class Session
{

	public function __construct()
	{
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
	}


	public function give($key)
	{
		return $_SESSION[$key];
	}

	public function put($key, $data)
	{
		return $_SESSION[$key] = $data;
	}

	public function all()
	{
		return $_SESSION;
	}
}