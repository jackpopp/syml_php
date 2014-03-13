<? 

class HomeController extends BaseController
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
		echo 'Show '.$id;
	}

	public function create()
	{
		echo 'create';
	}
}