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
    
Mapping a route to an anonymous function

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

Controller classes can be used to group logic for rendering data from models in views/responses or taking input from views/requests and maniuplate or pass to models.
Controllers are stored in app/controllers and are prepending by the word controller.

**Defining a controller**

	Below is an example of defining a controller, the controller and index function can be mapped in the routes config with 'home#index' as the second paramater of a route.

    class HomeController 
	{
		function index()
		{
			echo 'This is the home page';
		}
	}

Models - ORM
-----

Syml comes with a simple Model ORM that follows the ActiveRecord pattern and allows for easy database interaction. 
Models are stored in the app/models folder must extend from the base model under the Syml namespace (Syml\Model). 

**Defining a model**

Below is an example of defining a model the model extends the Syml\Model class and the table attribute must be the same as the table in the database.

	class UserModel extends Syml\Model 
	{
		protected $table = 'Users';
	}

**Finding a record**

Below is an example of finding a record in the database we pass the primary key which by default is the id and if a record is found it is returned as an instace of the model class.
	
	$userModel = new UserModel();
	$user = $userModel->find(1);

**Retriving all records**
	
To retrive all records we can call the all method and will be returned an array of model instances.

	$userModel = new UserModel();
	$users = $userModel->all();

**Setting  and getting attributes on a modal**
	
To set an attribute on a modal we just set the property on the class, getting an attribute is as simple as calling that attribute.

	$user = new UserModel();
	$user->name = "Johnaldo";
	$user->surname = "Jones";
	echo $user->name;

**Insert or Update a record**

To insert a record we simply call the save function on the model, this will insert the record in the database and populate the primary key.
We can also update a record by calling the save function which will update the record that coresponds to the primiary key attribute on the modal.

	// insert
	$user = new UserModel();
	$user->name = "Johnaldo";
	$user->surname = "Jones";
	$user->save();

	// update
	$user->name = "John";
	$user->save();


Views
-----

Views are used to render templates and data from the controller they are one of the primary ways of responding to requests.
The preferred method of accessing the View class is to inject the dependency via the controllers constrctor and allowing Syml's IOC container to resolve the class at runtime.
In order to do this we **must** type hint the arguement in the constructor or the IOC container will not be able to resolve it. 

**Injecting the view as a dependency in a controller**
	
	class HomeController
	{
		protected $view;

		public function __construct(View $view)
		{
			$this->view = $view;
		}
	}

An easier but less preferable way to use the View class is to call an instance of the IOC and execture the make method to resolve an instance of the View class.

**Resolving the View class from IOC**

	$view = IOC()->make('Syml\View');

**Rendering a view**

We can render a view by using the render method, views are stored in the app/views folder and are a flat php file.
The views file name must not include the .php extention as this is added by the render function, by default rendering a view will imedately output the render.

**Passing data to a view**

	$view->render('home/index');

We can pass dat to a view using the second paramater of the render function and passing an array of data, the keys in the array can then be used to access the data passed into the view.

	$data = array('user_id' => 5);
	$view->render('home/index', $data);

	// id can be accessed in the view as follows
	echo $id;

**Rendering the view as a string**

If we want to return the rendered view as a string to use later and not be imedately rendered we can pass a third paramater of true to render as string
	
	$data = array('user_id' => $data);
	$renderedView = $view->render('home/index', $data, true);

	echo $renderedView;

**Rendering a view within a layout**

We can also render a view within a layout, for example if you have a generic header and footer for each page we can render out different view templates within this consistent layout structure.
By default the layout will be the application.php layout in the layouts folder.

	$this->view->renderInLayout('home/index', $data);

We can specify a layout adding a third paramater, within the layout we can access the rendered view for body content by calling $__body__

	$this->view->renderInLayout('home/index', $data, 'homeLayout');

**Setting the http response code**

We can also set a http response code 
	
	$this->view->setResponse(201)->renderInLayout('home/created', $data);

Session
-----

Input
-----

IOC
-----

Helpers
-----