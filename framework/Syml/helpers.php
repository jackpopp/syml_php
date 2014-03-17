<?php  session_start();
# generate or find existing csrf token and return

function csrfToken() 
{
	if ( ! empty($_SESSION['csrfToken']))
		return $_SESSION['csrfToken'];

	$_SESSION['csrfToken'] = base64_encode(openssl_random_pseudo_bytes(16));

	return $_SESSION['csrfToken'];
}

# check for valid auth token

function checkAuthenticityToken()
{
	( empty($_SESSION['csrfToken']) || empty($_POST['csrfToken']) || ($_SESSION['csrfToken'] != $_POST['csrfToken']) ) ? false : true;
}

# return a new instance of the IOC container

function IOC()
{
	return new Syml\IOC();
}

function toSnakeCase($input) {
  preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
  $ret = $matches[0];
  foreach ($ret as &$match) {
    $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
  }
  return implode('_', $ret);
}