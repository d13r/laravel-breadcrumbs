<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Config;

class SkipFileLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        Config::set('breadcrumbs.files', []);
    }

    public function testLoading()
    {
        // I can't think of a way to actually test this - see code coverage (ServiceProvider::registerBreadcrumbs())
        $this->assertTrue(true);
    }
}
