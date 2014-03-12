<?php namespace Syml;

# hold request uri, http verb, posts, gets, puts and headers

class Request 
{

	private $requestURI;
	private $requestMethod;

	public function __construct()
	{
		$this->setRequestMethod();
		$this->setRequestURI($this->cleanRequestURI());
	}

	public function setRequestURI($requestURI)
	{
		$this->requestURI = $requestURI;
		return $this;
	}

	public function getRequestURI()
	{
		return $this->requestURI;
	}

	public function setRequestMethod()
	{
		$this->requestMethod = $_SERVER['REQUEST_METHOD'];
	}

	public function getRequestMethod()
	{
		return $this->requestMethod;
	}
	
	public function cleanRequestURI(){
		// get the root and the request uri and split into array using forward slash delimiter
		$rootValues = explode('/', $_SERVER['DOCUMENT_ROOT']);
		$requestValues = explode('/', $_SERVER['REQUEST_URI']);

		// loop through the request and remove any matching array values to leave us with the correct request
		foreach($requestValues as $key => $value)
			if (in_array($value, $rootValues)) { unset($requestValues[$key]); }

		// implode reqeust into string and remove any query string
		$request = implode('/', $requestValues);
		$request = preg_replace('/\?'.$_SERVER['QUERY_STRING'].'/', '', $request);

		return $request;
	}
}