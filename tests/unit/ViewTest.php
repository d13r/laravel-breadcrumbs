<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\View;
use Illuminate\Support\Collection;

class ViewTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->view = app(View::class);

        $this->breadcrumbs = new Collection([
            (object) [
                'title' => 'Home',
                'url'   => '/',
            ],
            (object) [
                'title' => 'Not a link',
                'url'   => null, // Test non-links
            ],
            (object) [
                'title' => 'Blog & < >', // Test HTML escaping
                'url'   => '/blog',
            ],
            (object) [
                'title' => 'Sample Post',
                'url'   => '/blog/123',
            ],
        ]);
    }

    public function testBootstrap2()
    {
        $html = $this->view->render('breadcrumbs::bootstrap2', $this->breadcrumbs)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ul class="breadcrumb">
                <li>
                    <a href="/">Home</a>
                    <span class="divider">/</span>
                </li>
                <li class="active">
                    Not a link
                    <span class="divider">/</span>
                </li>
                <li>
                    <a href="/blog">Blog &amp; &lt; &gt;</a>
                    <span class="divider">/</span>
                </li>
                <li class="active">
                    Sample Post
                </li>
            </ul>
        ', $html);
    }

    public function testBootstrap3()
    {
        $html = $this->view->render('breadcrumbs::bootstrap3', $this->breadcrumbs)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li class="active">Not a link</li>
                <li><a href="/blog">Blog &amp; &lt; &gt;</a></li>
                <li class="active">Sample Post</li>
            </ol>
        ', $html);
    }
}
