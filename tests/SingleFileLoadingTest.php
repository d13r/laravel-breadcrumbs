<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use Config;

class SingleFileLoadingTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('breadcrumbs.files', __DIR__ . '/routes/breadcrumbs.php');
    }

    public function testLoading()
    {
        $html = Breadcrumbs::render('single-file-test')->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Loaded</li>
            </ol>
        ', $html);
    }
}
