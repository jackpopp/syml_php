<?php
// registers routes via $router
// here are some examples below

// route, controller#function or anonymous function

/***
*
* --------------------
* Routes config
* --------------------
*
*
*
*
*
***/

$router->get('/', 'home#index');
$router->get('home', 'home#index');

$router->get('home/{title}', 'home#show');

/*
$router->get('home/{id}', function($id){
	echo $id;
});
*/

$router->post('home', 'home#create');