<?php namespace Syml;

use \PDO as PDO;
use Syml\ModelCollection as ModelCollection;

class Model
{

	protected $config;
	protected $attributes = array();
	protected $connection = null;
	protected $primaryKey = 'id';
	protected $table = 'users';
	protected $resultArrayOfSelf = false;

	public function __construct($attributes = array())
	{
		$this->attributes = $attributes;
		$this->config = require(__DIR__.'/../../app/config/database.php');
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

	public function getResultArrayOfSelf()
	{
		return $this->resultArrayOfSelf;
	}

	public function setResultArrayOfSelf($resultArrayOfSelf)
	{
		$this->resultArrayOfSelf = $resultArrayOfSelf;
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

	public function findBy($column, $value)
	{
		$connection = $this->connect()->getConnection();
		$query = $connection->prepare("SELECT * FROM ".$this->getTable()." WHERE ".$column." = ?");
    	$query->execute(array($value));
    	$count = $query->rowCount();

    	if ($count > 0)
    	{
    		if ($count == 1)
    		{
    			return new self($query->fetch());
    		}
    		else
    		{
    			$models = array();

    			while ($result = $query->fetch())
    				$models[] = new self($result);

		    	$modelCollection = new ModelCollection();
		    	$modelCollection->setModels($models);
		    	return $modelCollection;
    		}
    	}		
	}

	public function all()
	{
		$connection = $this->connect()->getConnection();
		$query = $connection->prepare("SELECT * FROM ".$this->getTable());
    	$query->execute();

    	$models = array();

    	# creates a model collection object and pass in all the records found

    	while ($result = $query->fetch())
    		$models[] = new self($result);

    	$modelCollection = new ModelCollection();
    	$modelCollection->setModels($models);
    	return $modelCollection;
	}

	# save or update model
	# if we have an id do an update if we dont do an insert

	public function save()
	{
		# build insert/update string based on params
		$valuesArray = [];
		$columnsArray = [];
		foreach ($this->getAttributes() as $key => $value)
		{
			if ($key != 'id')
			{
				$valuesArray[] = ':'.toSnakeCase($key);
				$columnsArray[] = toSnakeCase($key);
			}
			
		}

		$insertArray = [];
		foreach ($this->getAttributes() as $key => $value)
			$insertArray[':'.toSnakeCase($key)] = $value;
		$connection = $this->connect()->getConnection();

		# TODO - dont hardcode this instead check if primary key is in the attributes array
		if ( ! $this->id )
		{
			$valuesString = implode(',', $valuesArray);
			$columnsString = implode(',', $columnsArray);

			$query = $connection->prepare("INSERT INTO ".$this->getTable()." (".$columnsString.") VALUES(".$valuesString.")");
			$query->execute($insertArray);
			$this->id = $connection->lastInsertId();
		}
		else
		{
			$updateArray = [];
			foreach ($valuesArray as $key => $value) 
				$updateArray[] = $columnsArray[$key].' = '.$valuesArray[$key];
			$updateString = implode(',', $updateArray);

			$query = $connection->prepare("UPDATE ".$this->getTable()." SET ".$updateString." WHERE id = :id");
			$query->execute($insertArray);
		}
	}

	public function delete()
	{
		if ( $this->id )
		{
			$connection = $this->getConnection();
			$query = $this->connection->prepare("DELETE FROM ".$this->getTable()." WHERE id = :id");
			$query->execute(array('id' => $this->id));
			unset($this->id);
		}
	}

	public function queryRaw($query, $values)
	{

	}

	public function connect()
	{
		if ($this->getConnection() == null)
		{
			$connection = new PDO("mysql:host=".$this->config['host'].";dbname=".$this->config['dbname'].";port=".$this->config['port'].";", $this->config['username'], $this->config['password']);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->setConnection($connection);
		}	
		return $this;
	}

	public function toArray()
	{
		$attributes = $this->getAttributes();
		$modelArray = array();

		foreach ($attributes as $key => $value)
			$modelArray[$key] = $value;

		return $modelArray;
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

	# allow for magic find by methods
	# update to allows findByExampleAndExample

	public function __call($name, $arguments)
	{
		$functionStringArray = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);
		if ($functionStringArray[0] == 'find' && $functionStringArray[1] == 'By')
		{
			array_shift($functionStringArray);
			array_shift($functionStringArray);
			$function = implode('_', $functionStringArray);
			$function = strtolower($function);
			return $this->findBy($function, $arguments[0]);
		}
		else
			throw new \Exception("No function found.");
			
	}
}