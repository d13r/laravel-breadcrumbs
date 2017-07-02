<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider as BreadcrumbsServiceProvider;

class CustomChildServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CustomChildBreadcrumbsServiceProvider::class,
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

class CustomChildBreadcrumbsServiceProvider extends BreadcrumbsServiceProvider
{
    public function registerBreadcrumbs()
    {
        Breadcrumbs::register('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', '/');
        });
    }
}
