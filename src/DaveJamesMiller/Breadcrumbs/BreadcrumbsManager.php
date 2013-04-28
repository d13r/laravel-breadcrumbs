<?php namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\View\Environment as ViewEnvironment;

class BreadcrumbsManager {

	protected $callbacks = array();

	protected $environment;

	protected $view = 'breadcrumbs::bootstrap';

	public function __construct(ViewEnvironment $environment)
	{
		$this->environment = $environment;
	}

	public function getView()
	{
		return $this->view;
	}

	public function setView($view)
	{
		$this->view = $view;
	}

	public function register($name, Callable $callback)
	{
		$this->callbacks[$name] = $callback;
	}

	public function generate($name, $args = array())
	{
		if (!is_array($args)) $args = array_slice(func_get_args(), 1);

		$generator = new BreadcrumbsGenerator($this->callbacks);
		$generator->call($name, $args);
		return $generator->toArray();
	}

	public function render($name, $args = array())
	{
		if (!is_array($args)) $args = array_slice(func_get_args(), 1);

		$breadcrumbs = $this->generate($name, $args);

		return $this->environment->make($this->view, compact('breadcrumbs'))->render();
	}

}
