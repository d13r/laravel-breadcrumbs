<?php namespace DaveJamesMiller\Breadcrumbs;

class BreadcrumbsGeneratorException extends \Exception {}

class BreadcrumbsGenerator {

	protected $callbacks = array();
	protected $breadcrumbs = array();

	public function __construct(array $callbacks)
	{
		$this->callbacks = $callbacks;
	}

	public function get()
	{
		return $this->breadcrumbs;
	}

	public function set(array $breadcrumbs)
	{
		$this->breadcrumbs = $breadcrumbs;
	}

	public function call($name, $args = array())
	{
		if (!isset($this->callbacks[$name])) throw new BreadcrumbsGeneratorException("Invalid breadcrumb: $name");

		if (!is_array($args)) $args = array_slice(func_get_args(), 1);

		array_unshift($args, $this);

		call_user_func_array($this->callbacks[$name], $args);
	}

	public function parent($name, $args = array())
	{
		if (!is_array($args)) $args = array_slice(func_get_args(), 1);

		$this->call($name, $args);
	}

	public function push($title, $url = null)
	{
		$this->breadcrumbs[] = (object) array(
			'title' => $title,
			'url' => $url,
			// These will be altered later where necessary:
			'first' => false,
			'last' => false,
		);
	}

	public function toArray()
	{
		$breadcrumbs = $this->breadcrumbs;

		// Add first & last indicators
		if ($breadcrumbs) {
			$breadcrumbs[0]->first = true;
			$breadcrumbs[count($breadcrumbs) - 1]->last = true;
		}

		return $breadcrumbs;
	}

}
