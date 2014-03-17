Syml PHP
========
----
Syml is a small PHP framework for developing web applications it offers several features to speed up development and follows a Model View Controller architecture.

Routing
-----
-----

Routing allows us to handle the different reqeusts that are made to the server, we register routes through the routes config files and these routes are mapped to a controller and function or an anonymous function. 

Syml's router allows us to register routes for different http verbs (get/post/put/patch/delete) and also allows for dynamic URLs where variable values are mapped and passed to the mapped function

**Mapping routes**

Mapping a route to a controller and function

    $router->get('route', 'controller#function');
    
Mapping a route to an anonymouse function

    $router->get('route', function(){ 
        echo 'function';
    });
    
Mapping the base route of your application

    $router->get('/', 'home#index');

Mapping a dynamic route
    
    $router->get('users/{id}', 'users#show');
    
    // variables are accesable in the order defined in the route
    // function ($id) { echo $id; }
    
    $router->get('users/{id}', function($id){ 
        echo $id;
    });
    
Mapping different http verbs

    $router->get('users', 'users#index');
    $router->post('users', 'users#create');
    $router->delete('users/{id}', 'users#delete');
    $router->put('users/{id}', 'users#update');
    $router->patcha('users/{id}', 'users#update');

Controllers
-----

Models 
-----

Views
-----

Session
-----

Input
-----

IOC
-----

Helpers
-----