<?php

abstract class TestCase extends Orchestra\Testbench\TestCase {

	protected function getPackageProviders()
	{
		return [
			'DaveJamesMiller\Breadcrumbs\ServiceProvider',
		];
	}

	protected function getPackageAliases()
	{
		return [
			'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facade'
		];
	}

	public function setUp()
	{
		parent::setUp();

		$this->loadServiceProvider();
	}

	protected function loadServiceProvider()
	{
		// Need to trigger register() to test the views
		$this->app->make('breadcrumbs');
	}

	public function tearDown()
	{
		$this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());

		Mockery::close();
	}

}
