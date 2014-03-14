<?php namespace Syml;

class View 
{

	/**
	* Renders a template using the data provided and template specified
	*
	* @param string   $viewTemplate    The template we wish to render
	* @param array    $data            The data to pass to the view
	*/
	public function render($viewTemplate, $data)
	{
		foreach ($data as $key => $variable) {
			$$key = $variable;
		}
		return include(__DIR__.'/../../app/views/'.$viewTemplate.'.php');
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
			$layoutBody = ob_get_contents();
		ob_end_clean();

		include(__DIR__.'/../../app/views/layouts/'.$layoutTemplate.'.php');	
	}
}