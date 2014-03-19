Syml PHP
========
----
Syml is a small PHP framework for developing web applications it offers several features to speed up development and follows a Model View Controller architecture.

Routing
-----
-----

Routing allows us to handle the different reqeusts that are made to the server, we register routes through the routes config files and these routes are mapped to a controller and function or an anonymous function. 

Syml's router allows us to register routes for different http verbs (get/post/put/patch/delete) and also allows for dynamic URLs where variable values are mapped and passed to the mapped function.

The routers config file can be found in app/config/routes.php.

**Mapping routes**

Mapping a route to a controller and function

```php
$router->get('route', 'controller#function');
```
    
Mapping a route to an anonymous function

```php
$router->get('route', function(){ 
    echo 'function';
});
```

Mapping the base route of your application
```php
    $router->get('/', 'home#index');
```
Mapping a dynamic route

 ```php   
$router->get('users/{id}', 'users#show');

// variables are accesable in the order defined in the route
// function ($id) { echo $id; }

$router->get('users/{id}', function($id){ 
    echo $id;
});
```    
Mapping different http verbs

```php
$router->get('users', 'users#index');
$router->post('users', 'users#create');
$router->delete('users/{id}', 'users#delete');
$router->put('users/{id}', 'users#update');
$router->patcha('users/{id}', 'users#update');
```

Controllers
-----

Controller classes can be used to group logic for rendering data from models in views/responses or taking input from views/requests and maniuplate or pass to models.
Controllers are stored in app/controllers and are prepending by the word controller.

**Defining a controller**

    Below is an example of defining a controller, the controller and index function can be mapped in the routes config with 'home#index' as the second paramater of a route.

```php
class HomeController 
{
	function index()
	{
		echo 'This is the home page';
	}
}
```

Models - ORM
-----

Syml comes with a simple Model ORM that follows the ActiveRecord pattern and allows for easy database interaction. 
Models are stored in the app/models folder must extend from the base model under the Syml namespace (Syml\Model). 

**Defining a model**

Below is an example of defining a model the model extends the Syml\Model class and the table attribute must be the same as the table in the database.

```php
class UserModel extends Syml\Model 
{
	protected $table = 'Users';
}
```

**Finding a record**

Below is an example of finding a record in the database we pass the primary key which by default is the id and if a record is found it is returned as an instace of the model class.

```php
$userModel = new UserModel();
$user = $userModel->find(1);
```

**Retriving all records**
	
To retrive all records we can call the all method and will be returned an array of model instances.

```php
$userModel = new UserModel();
$users = $userModel->all();
```

**Setting  and getting attributes on a modal**
	
To set an attribute on a modal we just set the property on the class, getting an attribute is as simple as calling that attribute.

```php
$user = new UserModel();
$user->name = "Johnaldo";
$user->surname = "Jones";
echo $user->name;
```

**Insert or Update a record**

To insert a record we simply call the save function on the model, this will insert the record in the database and populate the primary key.
We can also update a record by calling the save function which will update the record that coresponds to the primiary key attribute on the modal.

```php
// insert
$user = new UserModel();
$user->name = "Johnaldo";
$user->surname = "Jones";
$user->save();

// update
$user->name = "John";
$user->save();
```

**Delete a record**

We can delete a record from the database by calling delete on a instance of a model, doing so will delete it from database and unset the primiary key on the instance of the model.

```php
$userModel = new UserModel();
$user = $userModel->find(1);
$user->delete();
```

**Find by columnname**

We can find a model by any column using the findBy* function, to do this we call a functino with findByCamelCaseColumn and the value we are looking for.

```php
$user = UserModel();
$user->findByEmailAddress('jack@jackpopp.com');
```

Views
-----

Views are used to render templates and data from the controller they are one of the primary ways of responding to requests.
we can access the View class by injecting the dependency via the controllers constrctor and allowing Syml's IOC container to resolve the class at runtime.
In order to do this we **must** type hint the arguement in the constructor or the IOC container will not be able to resolve it. 

**Injecting the view as a dependency in a controller**

```php
class HomeController
{
	protected $view;

	public function __construct(Syml\View $view)
	{
		$this->view = $view;
	}
}
```

An easier but less preferable way to use the View class is to call an instance of the IOC and exectue the make method to resolve an instance of the View class.

**Resolving the View class from IOC**

```php
$view = IOC()->make('Syml\View');
```

**Rendering a view**

We can render a view by using the render method, views are stored in the app/views folder and are a flat php file.
The views file name must not include the .php extention as this is added by the render function, by default rendering a view will imedately output the render.

```php
$view->render('home/index');
```

**Passing data to a view**


We can pass data to a view using the second paramater of the render function and passing an array of data, the keys in the array can then be used to access the data passed into the view.

```php
$data = array('user_id' => 5);
$view->render('home/index', $data);

// id can be accessed in the view as follows
echo $id;
```

**Rendering the view as a string**

If we want to return the rendered view as a string to use later and not be imedately rendered we can pass a third paramater of true to render as string

```php
$data = array('user_id' => $data);
$renderedView = $view->render('home/index', $data, true);

echo $renderedView;
```

**Rendering a view within a layout**

We can also render a view within a layout, for example if you have a generic header and footer for each page we can render out different view templates within this consistent layout structure.
By default the layout will be the application.php layout in the layouts folder.

```php
$this->view->renderInLayout('home/index', $data);
```

We can specify a layout adding a third paramater, within the layout we can access the rendered view for body content by calling $__body__

```php
$this->view->renderInLayout('home/index', $data, 'homeLayout');
```

**Setting the http response code**

We can also set a http response code 

```php
$this->view->setResponse(201)->renderInLayout('home/created', $data);
```

Session
-----
The session class is a simple wrapper around PHPs Session implementation, if can be accessed in the controller by injecting it as a type hinted dependency or by invoking the IOC container and makeing an instance.

**Injecting the session dependency in a controller**

```php
class HomeController
{
	protected $session;

	public function __construct(Syml\Session $session)
	{
		$this->session = $session;
	}
}
```

**Setting a value in the session**

```php
$userId = 5;
$session->put('user_id', $userId);
```

**Getting a value from the session**
	
```php
$session->give('user_id');
```

**Get all values from the session**
	
```php
$session->all();
```

Input
-----
The input class in a simple wrapper around the $_POST and $_GET global arrays, we can inject the input class into a controller as a type hinted depenceny or by invokte the IOC container and making an insance.

**Injecting the session dependency in a controller**

```php
class HomeController
{
	protected $input;

	public function __construct(Syml\Input $input)
	{
		$this->input = $input;
	}
}
```

**Getting a value from the input class**

```php
$input->give('name');
```

The input class will look in both the $_POST and $_GET arrays to find the specified key and return

**Getting the entire POST array**

```php
$input->post();
```

**Getting the entire GET array**

```php
$this->get();
```

**Getting the POST and GET arrays**
	
```php
$input->all();
```


IOC Container
-----
The inversion of control container allows us to easy manage the dependencies of a class.
The container can automatically resolve type hinted dependencies on classes at runtime instead of hard coding dependancies, this allows greater flexbilty when developing our controllers and other classes. It also allows for easier testing as we can easliy mock dependencies and do not need to rely on running an full framework request to test controllers.

**Type hinted dependencies on a controller**
	
```php
class HomeController
{
	protected $session;
	protected $input

	public function __construct(Syml\Session $input, Syml\Input $input)
	{
		$this->session = $session;
		$this->input = $input;
	}
}
```

In the above code the IOC container will use reflection to determine the dependencies passed to the constructor and automatically instantiate, if the injected class has injected dependencies then the container will also resolve them.

In some cases we cant inject dependencies that we need for example in a view template we may wish to access the session class, in order access the class we can make use of the IOC helper function and the IOC make function.

**IOC Make function**

```php
IOC()->make('Syml\Session')->give('user_id'));
```

Here the container will insanitate and return the class we have specified and we can imediately call methods on the class.


Helpers
-----

There are several helper functions that can be used in the framework

**Cross Site Request Forgery Helpers**

A CSRF token can be generated by calling csrFToken()

```php
csrfToken()
// UnIYSGAW9W8MCNZ385KpMQ==
```

A CSRF token can be validated by calling checkAuthenticityToken(), the token must be passed as post variable with the csrfToken key

```php
checkAuthenticityToken()
// returns true or false
```

**IOC helper**

We can generate an insance of the IOC container by calling IOC() and can then run it functions

```php
IOC()->make('Syml\View')->render('home/home', array('title' => 'title'));
```

**General helpers**

The toCamelCase method takes a snake_case string value and returns a camelCase version

```php
toCamelCase('snake_case');
// snakeCase
```