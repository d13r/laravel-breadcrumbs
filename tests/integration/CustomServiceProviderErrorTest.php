<?php

class CustomServiceProviderErrorTest extends TestCase {

	protected function getPackageProviders()
	{
		return [
			// These are in the wrong order
			'CustomServiceProviderError',
			'DaveJamesMiller\Breadcrumbs\ServiceProvider',
		];
	}

	protected function loadServiceProvider()
	{
		// Disabled - we want to test the automatic loading instead
	}

	/**
	 * @expectedException DaveJamesMiller\Breadcrumbs\Exception
	 * @expectedExceptionMessage Breadcrumbs view not specified
	 */
	public function testRender()
	{
		Breadcrumbs::render('home');
	}

}

class CustomServiceProviderError extends Illuminate\Support\ServiceProvider {

	public function register()
	{
	}

	public function boot()
	{
		Breadcrumbs::register('home', function($breadcrumbs) {
			$breadcrumbs->push('Home', '/');
		});
	}

}
