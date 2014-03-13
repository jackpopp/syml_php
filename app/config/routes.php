<?php
// registers routes via $router
// here are some examples below

$router->get('pie', 'pie#index');
$router->get('pie/{id}', 'pie#show');
$router->post('pie', 'pie#create');