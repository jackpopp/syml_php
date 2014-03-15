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

function IOC()
{
	return new Syml\IOC();
}