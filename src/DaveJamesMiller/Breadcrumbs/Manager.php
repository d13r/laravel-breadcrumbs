<?php namespace DaveJamesMiller\Breadcrumbs;

use InvalidArgumentException;
use Illuminate\Routing\Router;

class Manager {

	protected $factory;
	protected $router;

	protected $callbacks = array();
	protected $view;

	protected $currentRoute;

	public function __construct($factory, Router $router)
	{
		if (! $factory instanceof \Illuminate\View\Factory && ! $factory instanceof \Illuminate\View\Environment) {
			throw new InvalidArgumentException('$factory must be an instance of either \Illuminate\View\Factory or \Illuminate\View\Environment');
		}

		$this->factory = $factory;
		$this->router = $router;
	}

	public function getView()
	{
		return $this->view;
	}

	public function setView($view)
	{
		$this->view = $view;
	}

	public function register($name, $callback)
	{
		$this->callbacks[$name] = $callback;
	}

	public function exists($name = null)
	{
		if (is_null($name)) {
			try {
				list($name) = $this->currentRoute();
			} catch (Exception $e) {
				return false;
			}
		}

		return isset($this->callbacks[$name]);
	}

	public function generate($name = null)
	{
		if (is_null($name))
			return $this->generateCurrent();

		$params = array_slice(func_get_args(), 1);
		return $this->generateArray($name, $params);
	}

	public function generateArray($name, $params = array())
	{
		$generator = new Generator($this->callbacks);
		$generator->call($name, $params);
		return $generator->toArray();
	}

	public function generateIfExists($name)
	{
		if ($this->exists($name))
			return call_user_func_array(array($this, 'generate'), func_get_args());
		else
			return array();
	}

	public function generateArrayIfExists($name, $params = array())
	{
		if ($this->exists($name))
			return call_user_func_array(array($this, 'generateArray'), func_get_args());
		else
			return array();
	}

	public function render($name = null)
	{
		if (is_null($name))
			return $this->renderCurrent();

		$params = array_slice(func_get_args(), 1);
		return $this->renderArray($name, $params);
	}

	public function renderIfExists($name = null)
	{
		if ($this->exists($name))
			return call_user_func_array(array($this, 'render'), func_get_args());
		else
			return '';
	}

	public function renderArray($name, $params = array())
	{
		$breadcrumbs = $this->generateArray($name, $params);

		return $this->factory->make($this->view, compact('breadcrumbs'))->render();
	}

	public function renderArrayIfExists($name = null)
	{
		if ($this->exists($name))
			return call_user_func_array(array($this, 'renderArray'), func_get_args());
		else
			return '';
	}

	protected function generateCurrent()
	{
		list($name, $params) = $this->currentRoute();

		return $this->generateArray($name, $params);
	}

	protected function renderCurrent()
	{
		list($name, $params) = $this->currentRoute();

		return $this->renderArray($name, $params);
	}

	protected function currentRoute()
	{
		if ($this->currentRoute)
			return $this->currentRoute;

		$route = $this->router->current();

		if (is_null($route))
			return $this->currentRoute = array('', array());

		$name = $route->getName();

		if (is_null($name)) {
			$uri = head($route->methods()) . ' ' . $route->uri();
			throw new Exception("The current route ($uri) is not named - please check routes.php for an \"as\" parameter");
		}

		$params = $route->parameters();

		return $this->currentRoute = array($name, $params);
	}

	public function setCurrentRoute($name)
	{
		$params = array_slice(func_get_args(), 1);

		$this->setCurrentRouteArray($name, $params);
	}

	public function setCurrentRouteArray($name, $params = array())
	{
		$this->currentRoute = array($name, $params);
	}

	public function clearCurrentRoute()
	{
		$this->currentRoute = null;
	}

}
