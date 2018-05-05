<?php

namespace BreadcrumbsTests;

use Breadcrumbs;

class OutputTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // Home (Normal link)
        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', url('/'));
        });

        // Home > Blog (Not a link)
        Breadcrumbs::for('blog', function ($trail) {
            $trail->parent('home');
            $trail->push('Blog');
        });

        // Home > Blog > [Category] (Active page)
        Breadcrumbs::for('category', function ($trail, $category) {
            $trail->parent('blog');
            $trail->push($category->title, url("blog/category/{$category->id}"));
        });

        $this->category = (object) [
            'id'    => 456,
            'title' => 'Sample Category',
        ];

        $this->expectedHtml = '
            <nav>
                <ol>
                    <li><a href="http://localhost">Home</a></li>
                    <li>Blog</li>
                    <li class="current">Sample Category</li>
                </ol>
            </nav>
        ';
    }

    public function testBladeRender()
    {
        // {{ Breadcrumbs::render('category', $category) }}
        $html = view('view-blade')->with('category', $this->category)->render();

        $this->assertXmlStringEqualsXmlString($this->expectedHtml, $html);
    }

    public function testBladeSection()
    {
        // @section('breadcrumbs', Breadcrumbs::render('category', $category))
        $html = view('view-section')->with('category', $this->category)->render();

        $this->assertXmlStringEqualsXmlString($this->expectedHtml, $html);
    }

    public function testPhpRender()
    {
        /* <?= Breadcrumbs::render('category', $category) ?> */
        $html = view('view-php')->with('category', $this->category)->render();

        $this->assertXmlStringEqualsXmlString($this->expectedHtml, $html);
    }
}
