<?php namespace DaveJamesMiller\Breadcrumbs;

use Illuminate\Contracts\View\Factory as ViewFactory;

class View {

	protected $factory;

	public function __construct(ViewFactory $factory)
	{
		$this->factory = $factory;
	}

	public function render($view, $breadcrumbs)
	{
		return $this->factory->make($view, compact('breadcrumbs'))->render();
	}

}
