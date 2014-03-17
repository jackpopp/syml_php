<?php namespace Syml;

use \PDO as PDO;

class Model
{

	protected $attributes = array();
	protected $connection = null;
	protected $primaryKey = 'id';
	protected $table = 'users';

	public function __construct($attributes = array())
	{
		$this->attributes = $attributes;
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;
		return $this;
	}

	public function getConnection()
	{
		return $this->connection;
	}

	public function setConnection($connection)
	{
		$this->connection = $connection;
		return $this;
	}

	public function getTable()
	{
		return $this->table;
	}

	public function find($id)
	{
		$connection = $this->connect()->getConnection();
		$query = $connection->prepare("SELECT * FROM ".$this->getTable()." WHERE id = ?");
    	$query->execute(array($id));
    	return new self($query->fetch());
	}

	public function all()
	{
		$connection = $this->connect()->getConnection();
		$query = $connection->prepare("SELECT * FROM ".$this->getTable());
    	$query->execute();

    	$models = array();

    	while ($result = $query->fetch())
    		$models[] = new self($result);
    	return $models;
	}

	# save or update model
	# if we have an id do an update if we dont do an insert

	public function save()
	{
		# build inset string based on params
		$valuesArray = [];
		$columnsArray = [];
		foreach ($this->getAttributes() as $key => $value)
		{
			$valuesArray[] = ':'.toSnakeCase($key);
			$columnsArray[] = toSnakeCase($key);
		}
			
		$valuesString = implode(',', $valuesArray);
		$columnsString = implode(',', $columnsArray);

		$insertArray = [];
		foreach ($this->getAttributes() as $key => $value)
			$insertArray[':'.toSnakeCase($key)] = $value;

		$connection = $this->connect()->getConnection();
		# dont hardcode this instead check if primary key is in the attributes array
		if (empty($this->id))
		{
			$query = $connection->prepare("INSERT INTO ".$this->getTable()." (".$columnsString.") VALUES(".$valuesString.")");
		}
		else
		{
			$query->connection("UPDATE someTable SET name = :name WHERE id = :id");
		}
		$query->execute($insertArray);
	}

	public function delete()
	{

	}

	public function connect()
	{
		if ($this->getConnection() == null)
		{
			$connection = new PDO("mysql:host=localhost;dbname=syml;port=8889;", 'root', 'root');
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->setConnection($connection);
		}	
		return $this;
	}

	public function __get($key)
	{
		if (array_key_exists($key, $this->getAttributes()))
			return $this->getAttributes()[$key];
	}

	public function __set($key, $value)
	{
		$attributes = $this->getAttributes();
		$attributes[$key] = $value;
		$this->setAttributes($attributes);
	}
}