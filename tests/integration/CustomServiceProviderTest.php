<?php

namespace BreadcrumbsTests;

use Breadcrumbs;

class CustomServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            CustomServiceProvider::class,
        ];
    }

    public function testRender()
    {
        $html = Breadcrumbs::render('home')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Home</li>
            </ol>
        ', $html);
    }
}

class CustomServiceProvider extends \DaveJamesMiller\Breadcrumbs\ServiceProvider
{
    public function registerBreadcrumbs()
    {
        Breadcrumbs::register('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', '/');
        });
    }
}
