<?php namespace DaveJamesMiller\Breadcrumbs;

class Getter {

	protected $breadcrumb  = null;
	protected $callback    = null;

	public function get($callback, $params)
	{
		$this->callback    = $callback;

		$this->call($params);
		return $this->breadcrumb;
	}

	protected function call($params)
	{
		array_unshift($params, $this);

		call_user_func_array($this->callback, $params);
	}

	public function parent($name)
	{
		// Do nothing on parent
	}

	public function parentArray($name, $params = [])
	{
		// Do nothing on parent
	}

	public function push($title, $url = null, array $data = [])
	{
		$this->breadcrumb = (object) [
			'title' => $title,
			'url' => $url
		];
	}

}
