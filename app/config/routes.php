<?php
// registers routes via $router

$router->get('pie', 'pie#index');
$router->post('pie', 'pie#create');