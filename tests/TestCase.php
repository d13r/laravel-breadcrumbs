<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\Facade;
use DaveJamesMiller\Breadcrumbs\Manager;
use DaveJamesMiller\Breadcrumbs\ServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Breadcrumbs' => Facade::class,
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
        $this->app->make(Manager::class);
    }

    public function tearDown()
    {
        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());

        Mockery::close();
    }
}
