<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\CurrentRoute;
use Route;
use stdClass;

class CurrentRouteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->currentRoute = app(CurrentRoute::class);
    }

    public function testNamedRoute()
    {
        Route::name('sampleroute')->get('/sample', function () {
            $this->assertSame(['sampleroute', []], $this->currentRoute->get());
        });

        $this->call('GET', '/sample');
    }

    public function testNamedRouteWithParameters()
    {
        $object = new stdClass;

        Route::bind('object', function () use ($object) {
            return $object;
        });

        Route::name('sampleroute')->get('/sample/{text}/{object}', function () use ($object) {
            $this->assertSame(['sampleroute', ['blah', $object]], $this->currentRoute->get());
        });

        $this->call('GET', '/sample/blah/object');
    }

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException
     * @expectedExceptionMessage The current route (GET /sample/unnamed) is not named
     */
    public function testUnnamedRoute()
    {
        Route::get('/sample/unnamed', function () {
            $this->currentRoute->get();
        });

        // Laravel 5.3+ catches the exception
        throw $this->call('GET', '/sample/unnamed')->exception;
    }

    public function testSet()
    {
        $this->currentRoute->set('custom', [1, 'blah']);

        Route::name('sampleroute')->get('/sample', function () {
            $this->assertSame(['custom', [1, 'blah']], $this->currentRoute->get());
        });

        $this->call('GET', '/sample');
    }

    public function testClear()
    {
        $this->currentRoute->set('custom', [1, 'blah']);
        $this->currentRoute->clear();

        Route::name('sampleroute')->get('/sample', function () {
            $this->assertSame(['sampleroute', []], $this->currentRoute->get());
        });

        $this->call('GET', '/sample');
    }
}
