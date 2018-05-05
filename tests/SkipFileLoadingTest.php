<?php

namespace BreadcrumbsTests;

class SkipFileLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('breadcrumbs.files', []);
    }

    public function testLoading()
    {
        // I can't think of a way to actually test this - see code coverage (ServiceProvider::registerBreadcrumbs())
        $this->assertTrue(true);
    }
}
