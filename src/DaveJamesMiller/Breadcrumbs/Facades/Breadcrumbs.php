<?php namespace DaveJamesMiller\Breadcrumbs\Facades;

use Illuminate\Support\Facades\Facade;

class Breadcrumbs extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'breadcrumbs'; }

}
