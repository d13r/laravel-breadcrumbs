<?php namespace DaveJamesMiller\Breadcrumbs;

class Generator {

	protected $breadcrumbs = [];
	protected $callbacks   = [];

	public function generate(array $callbacks, $name, $params)
	{
		$this->breadcrumbs = [];
		$this->callbacks   = $callbacks;

		$this->call($name, $params);
		return $this->toArray();
	}

	protected function call($name, $params)
	{
		if (!isset($this->callbacks[$name]))
			throw new Exception("Breadcrumb not found with name \"{$name}\"");

		array_unshift($params, $this);

		call_user_func_array($this->callbacks[$name], $params);
	}

	public function parent($name)
	{
		$params = array_slice(func_get_args(), 1);

		$this->call($name, $params);
	}

	public function parentArray($name, $params = [])
	{
		$this->call($name, $params);
	}

	public function push($title, $url = null, array $data = [])
	{
		$this->breadcrumbs[] = (object) array_merge($data, [
			'title' => $title,
			'url' => $url,
			// These will be altered later where necessary:
			'first' => false,
			'last' => false,
		]);
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
