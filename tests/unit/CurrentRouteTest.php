<?php

use DaveJamesMiller\Breadcrumbs\CurrentRoute;
use Mockery as m;

class CurrentRouteTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->currentRoute = app('DaveJamesMiller\Breadcrumbs\CurrentRoute');
	}

	public function testNamedRoute()
	{
		Route::get('/sample', ['as' => 'sampleroute', function()
		{
			$this->assertSame(['sampleroute', []], $this->currentRoute->get());
		}]);

		$this->call('GET', '/sample');
	}

	public function testNamedRouteWithParameters()
	{
		$object = new stdClass;

		Route::bind('object', function() use ($object) {
			return $object;
		});

		Route::get('/sample/{text}/{object}', ['as' => 'sampleroute', function() use ($object)
		{
			$this->assertSame(['sampleroute', ['blah', $object]], $this->currentRoute->get());
		}]);

		$this->call('GET', '/sample/blah/object');
	}

	/**
	 * @expectedException DaveJamesMiller\Breadcrumbs\Exception
	 * @expectedExceptionMessage The current route (GET /sample/unnamed) is not named - please check routes.php for an "as" parameter
	 */
	public function testUnnamedRoute()
	{
		Route::get('/sample/unnamed', function()
		{
			$this->assertSame(['sample', []], $this->currentRoute->get());
		});

		$this->call('GET', '/sample/unnamed');
	}

	public function testSet()
	{
		$this->currentRoute->set('custom', [1, 'blah']);

		Route::get('/sample', ['as' => 'sampleroute', function()
		{
			$this->assertSame(['custom', [1, 'blah']], $this->currentRoute->get());
		}]);

		$this->call('GET', '/sample');
	}

	public function testClear()
	{
		$this->currentRoute->set('custom', [1, 'blah']);
		$this->currentRoute->clear();

		Route::get('/sample', ['as' => 'sampleroute', function()
		{
			$this->assertSame(['sampleroute', []], $this->currentRoute->get());
		}]);

		$this->call('GET', '/sample');
	}

}
