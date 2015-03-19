<?php namespace DaveJamesMiller\Breadcrumbs;

class Manager {

	protected $currentRoute;
	protected $generator;
	protected $view;

	protected $callbacks = [];
	protected $viewName;
	protected $currentRouteManual;

	public function __construct(CurrentRoute $currentRoute, Generator $generator, View $view)
	{
		$this->generator    = $generator;
		$this->currentRoute = $currentRoute;
		$this->view         = $view;
	}

	public function register($name, $callback)
	{
		if (isset($this->callbacks[$name]))
			throw new Exception("Breadcrumb name \"{$name}\" has already been registered");
		$this->callbacks[$name] = $callback;
	}

	public function exists($name = null)
	{
		if (is_null($name)) {
			try {
				list($name) = $this->currentRoute->get();
			} catch (Exception $e) {
				return false;
			}
		}

		return isset($this->callbacks[$name]);
	}

	public function generate($name = null)
	{
		if (is_null($name))
			list($name, $params) = $this->currentRoute->get();
		else
			$params = array_slice(func_get_args(), 1);

		return $this->generator->generate($this->callbacks, $name, $params);
	}

	public function generateArray($name, $params = [])
	{
		return $this->generator->generate($this->callbacks, $name, $params);
	}

	public function generateIfExists($name = null)
	{
		if (is_null($name))
			list($name, $params) = $this->currentRoute->get();
		else
			$params = array_slice(func_get_args(), 1);

		if (!$this->exists($name))
			return [];

		return $this->generator->generate($this->callbacks, $name, $params);
	}

	public function generateIfExistsArray($name, $params = [])
	{
		if (!$this->exists($name))
			return [];

		return $this->generator->generate($this->callbacks, $name, $params);
	}

	/**
	 * @deprecated Since 3.0.0
	 * @see generateIfExistsArray
	 */
	public function generateArrayIfExists()
	{
		return call_user_func_array([$this, 'generateIfExistsArray'], func_get_args());
	}

	public function render($name = null)
	{
		if (is_null($name))
			list($name, $params) = $this->currentRoute->get();
		else
			$params = array_slice(func_get_args(), 1);

		$breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

		return $this->view->render($this->viewName, $breadcrumbs);
	}

	public function renderArray($name, $params = [])
	{
		$breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

		return $this->view->render($this->viewName, $breadcrumbs);
	}

	public function renderIfExists($name = null)
	{
		if (is_null($name))
			list($name, $params) = $this->currentRoute->get();
		else
			$params = array_slice(func_get_args(), 1);

		if (!$this->exists($name))
			return '';

		$breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

		return $this->view->render($this->viewName, $breadcrumbs);
	}

	public function renderIfExistsArray($name, $params = [])
	{
		if (!$this->exists($name))
			return '';

		$breadcrumbs = $this->generator->generate($this->callbacks, $name, $params);

		return $this->view->render($this->viewName, $breadcrumbs);
	}

	/**
	 * @deprecated Since 3.0.0
	 * @see renderIfExistsArray
	 */
	public function renderArrayIfExists()
	{
		return call_user_func_array([$this, 'renderIfExistsArray'], func_get_args());
	}

	public function setCurrentRoute($name)
	{
		$params = array_slice(func_get_args(), 1);

		$this->currentRoute->set($name, $params);
	}

	public function setCurrentRouteArray($name, $params = [])
	{
		$this->currentRoute->set($name, $params);
	}

	public function clearCurrentRoute()
	{
		$this->currentRoute->clear();
	}

	public function setView($view)
	{
		$this->viewName = $view;
	}

}
