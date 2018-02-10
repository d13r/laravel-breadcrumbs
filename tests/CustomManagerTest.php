<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Config;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsManager;
use Illuminate\Support\Collection;
use Route;
use URL;

class CustomManagerTest extends TestCase
{
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        // Need to inject this early, before the package is loaded, to simulate it being set in the config file
        $app['config']['breadcrumbs.manager-class'] = CustomManager::class;
    }

    public function testCustomManager()
    {
        $breadcrumbs = Breadcrumbs::generate();

        $this->assertSame('custom-manager', $breadcrumbs[0]);
    }
}

class CustomManager extends BreadcrumbsManager
{
    public function generate(string $name = null, ...$params): Collection
    {
        return new Collection(['custom-manager']);
    }
}
