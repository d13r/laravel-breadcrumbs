<?php

class CustomServiceProviderTest extends TestCase {

	protected function getPackageProviders()
	{
		return [
			'DaveJamesMiller\Breadcrumbs\ServiceProvider',
			'CustomServiceProvider',
		];
	}

	protected function loadServiceProvider()
	{
		// Disabled - we want to test the automatic loading instead
	}

	public function testRender()
	{
		$html = Breadcrumbs::render('home');
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/CustomServiceProvider.html', $html);
	}

}

class CustomServiceProvider extends Illuminate\Support\ServiceProvider {

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
