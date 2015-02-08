<?php namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Contracts\Routing\Registrar as Router;

class CurrentRoute {

	protected $route;
	protected $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	public function get()
	{
		if ($this->route)
			return $this->route;

		$route = $this->router->current();

		if (is_null($route))
			return ['', []];

		$name = $route->getName();

		if (is_null($name)) {
			$uri = head($route->methods()) . ' /' . $route->uri();
			throw new Exception("The current route ($uri) is not named - please check routes.php for an \"as\" parameter");
		}

		$params = array_values($route->parameters());

		return [$name, $params];
	}

	public function set($name, $params)
	{
		$this->route = [$name, $params];
	}

	public function clear()
	{
		$this->route = null;
	}

}
