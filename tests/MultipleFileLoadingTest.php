<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Config;

class MultipleFileLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('breadcrumbs.files', glob(__DIR__ . '/breadcrumbs/*.php'));
    }

    public function testLoading()
    {
        $html = Breadcrumbs::render('multiple-file-test')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li>Parent</li>
                <li class="current">Loaded</li>
            </ol>
        ', $html);
    }
}
