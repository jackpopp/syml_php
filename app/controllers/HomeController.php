<? 

class HomeController 
{
	public function __construct()
	{

	}

	public function index()
	{
		echo 'index';
	}

	public function show($id)
	{
		echo $id;
	}
}