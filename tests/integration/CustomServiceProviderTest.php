<?php

class CustomServiceProviderTest extends TestCase {

	protected function getPackageProviders($app)
	{
		return [
			CustomServiceProvider::class,
		];
	}

	public function testRender()
	{
		$html = Breadcrumbs::render('home');
		$this->assertXmlStringEqualsXmlFile(__DIR__ . '/../fixtures/CustomServiceProvider.html', $html);
	}

}

class CustomServiceProvider extends DaveJamesMiller\Breadcrumbs\ServiceProvider {

	public function registerBreadcrumbs()
	{
		Breadcrumbs::register('home', function($breadcrumbs) {
			$breadcrumbs->push('Home', '/');
		});
	}

}
