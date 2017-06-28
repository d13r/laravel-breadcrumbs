<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\ServiceProvider;

class DependantServiceProviderReversedTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            // These are in the wrong order, but still work because of the deferred loading
            DependantServiceProviderError::class,
            ServiceProvider::class,
        ];
    }

    protected function loadServiceProvider()
    {
        // Disabled - we want to test the automatic loading instead
    }

    public function testRender()
    {
        $html = Breadcrumbs::render('home')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol class="breadcrumb">
                <li class="active">Home</li>
            </ol>
        ', $html);
    }
}

class DependantServiceProviderError extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Breadcrumbs::register('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', '/');
        });
    }
}
