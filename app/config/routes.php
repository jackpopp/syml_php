<?php
// registers routes via $router
// here are some examples below

// route, controller#function or anonymous function

$router->get('home', 'home#index');

#$router->get('home/{id}', 'home#show');

$router->get('home/{id}', function($id){
	echo $id;
});

$router->post('home', 'home#create');