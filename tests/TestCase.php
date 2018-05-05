<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

abstract class TestCase extends TestbenchTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            BreadcrumbsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Breadcrumbs' => Breadcrumbs::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('view.paths', [__DIR__ . '/resources/views']);

        $app->config->set('breadcrumbs.view', 'breadcrumbs');
    }


}
