<?php

namespace BreadcrumbsTests;

use Config;
use DaveJamesMiller\Breadcrumbs\Facade;
use DaveJamesMiller\Breadcrumbs\ServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use View;

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

    protected function setUp()
    {
        parent::setUp();

        View::getFinder()->prependLocation(__DIR__ . '/resources/views');
        Config::set('breadcrumbs.view', 'breadcrumbs');
    }
}
