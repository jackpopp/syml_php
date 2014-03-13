<?php namespace Syml;

class View 
{
	public function render($viewTemplate, $data)
	{
		foreach ($data as $key => $variable) {
			$$key = $variable;
		}
		return include(__DIR__.'/../../app/views/'.$viewTemplate.'.php');
	}
}