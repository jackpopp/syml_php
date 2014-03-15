<?php namespace Syml;

class IOC 
{
	public function make($class)
	{
		# use reflection to get controllers paramaters
		# iterate through and instantiate each object, then push into 
		$classReflection = new \ReflectionClass($class);
		$parameters = array();

		if (method_exists($classReflection->getConstructor(), 'getParameters'))
		{
			$paramStrings = $classReflection->getConstructor()->getParameters();
			foreach ($paramStrings AS $paramString) {
				$paramName = $paramString->getClass()->name;
				$parameters[] = $this->make($paramName);
			}
		}	

		return $classReflection->newInstanceArgs($parameters);
	}
}