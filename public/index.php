<?php

	function getRequest(){
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

	echo '?'.$_SERVER['QUERY_STRING']; 
	echo '<br>';
	echo getRequest();
