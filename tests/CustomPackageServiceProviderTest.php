<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsManager;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Support\ServiceProvider;

class CustomPackageServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            BreadcrumbsServiceProvider::class,
            CustomPackageServiceProvider::class,
        ];
    }

    public function testRender()
    {
        $html = Breadcrumbs::render('home')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Home</li>
            </ol>
        ', $html);
    }
}

class CustomPackageServiceProvider extends ServiceProvider
{
    public function register() { }

    public function boot(BreadcrumbsManager $breadcrumbs)
    {
        $breadcrumbs->register('home', function (BreadcrumbsGenerator $breadcrumbs) {
            $breadcrumbs->push('Home', '/');
        });
    }
}
