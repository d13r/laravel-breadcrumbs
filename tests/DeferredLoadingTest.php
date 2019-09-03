<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Illuminate\Contracts\Console\Kernel;
use LogicException;

class DeferredLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        // If the service provider is loaded before the test starts, this file
        // will throw an exception.
        $app->config->set('breadcrumbs.files', [__DIR__ . '/routes/should-not-be-loaded.php']);
    }

    protected function resolveApplicationConsoleKernel($app)
    {
        // Disable the console kernel because it calls loadDeferredProviders()
        // which defeats the purpose of this test
        $app->singleton(Kernel::class, DisabledConsoleKernel::class);
    }

    public function testDeferredLoading()
    {
        $this->expectException(LogicException::class);

        // This triggers the service provider boot, which loads the breadcrumbs,
        // which throws an exception, which is caught by PHPUnit.
        Breadcrumbs::clearCurrentRoute();
    }
}

class DisabledConsoleKernel extends \Orchestra\Testbench\Console\Kernel
{
    public function bootstrap()
    {
    }
}
