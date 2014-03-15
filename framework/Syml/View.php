<?php namespace Syml;

use Syml\Input as Input;

class View 
{

	protected $responseCode = 200;

	public function __construct ()
	{
		http_response_code($this->getResponseCode());
	}

	public function setResponseCode($responseCode) 
	{
		$this->responseCode = $responseCode;
		http_response_code($this->getResponseCode());
		return $this;
	}

	public function getResponseCode()
	{
		return $this->responseCode;
	}

	/**
	* Renders a template using the data provided and template specified
	*
	* @param string   $viewTemplate    The template we wish to render
	* @param array    $data            The data to pass to the view
	*/
	public function render($viewTemplate, $data, $returnAsString = false)
	{
		foreach ($data as $key => $variable) {
			$$key = $variable;
		}
		if ($returnAsString)
		{
			$render = null;
			ob_start();
				return include(__DIR__.'/../../app/views/'.$viewTemplate.'.php');
				$render = ob_get_contents();
			ob_end_clean();
			return $render;
		}
		else
		{
			return include(__DIR__.'/../../app/views/'.$viewTemplate.'.php');
		}
	}

	/**
	* Renders a template within a layout using the data provided and template specified
	* Defaults to rendering the tempate within the application layout if the option $layoutTemplate
	* paramater is not provided
	*
	* @param string   $viewTemplate    The template we wish to render
	* @param array    $data            The data to pass to the view
	* @param string   $layoutTemplate  The layout to render the template within
	*/
	public function renderInLayout($viewTemplate, $data, $layoutTemplate = 'application')
	{
		foreach ($data as $key => $variable) {
			$$key = $variable;
		}

		$layoutBody = null;

		ob_start();
			include(__DIR__.'/../../app/views/'.$viewTemplate.'.php');
			$__body__ = ob_get_contents();
		ob_end_clean();

		include(__DIR__.'/../../app/views/layouts/'.$layoutTemplate.'.php');	
	}
}