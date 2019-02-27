<?php

namespace BreadcrumbsTests;

use Breadcrumbs;

class TemplatesTest extends TestCase
{
    protected function setUp(): void
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
    }

    public function testBootstrap2()
    {
        $html = Breadcrumbs::view('breadcrumbs::bootstrap2', 'category', $this->category)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ul class="breadcrumb">
                <li>
                    <a href="http://localhost">Home</a>
                    <span class="divider">/</span>
                </li>
                <li class="active">
                    Blog
                    <span class="divider">/</span>
                </li>
                <li class="active">
                    Sample Category
                </li>
            </ul>
        ', $html);
    }

    public function testBootstrap3()
    {
        $html = Breadcrumbs::view('breadcrumbs::bootstrap3', 'category', $this->category)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol class="breadcrumb">
                <li><a href="http://localhost">Home</a></li>
                <li class="active">Blog</li>
                <li class="active">Sample Category</li>
            </ol>
        ', $html);
    }

    public function testBootstrap4()
    {
        $html = Breadcrumbs::view('breadcrumbs::bootstrap4', 'category', $this->category)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="http://localhost">Home</a></li>
                <li class="breadcrumb-item active">Blog</li>
                <li class="breadcrumb-item active">Sample Category</li>
            </ol>
        ', $html);
    }

    public function testFoundation6()
    {
        $html = Breadcrumbs::view('breadcrumbs::foundation6', 'category', $this->category)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <nav aria-label="You are here:" role="navigation">
                <ul class="breadcrumbs">
                    <li><a href="http://localhost">Home</a></li>
                    <li class="disabled">Blog</li>
                    <li class="current"><span class="show-for-sr">Current:</span> Sample Category</li>
                </ul>
            </nav>
        ', $html);
    }

    public function testMaterialize()
    {
        $html = Breadcrumbs::view('breadcrumbs::materialize', 'category', $this->category)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <nav>
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a class="breadcrumb" href="http://localhost">Home</a>
                        <span class="breadcrumb">Blog</span>
                        <span class="breadcrumb">Sample Category</span>
                    </div>
                </div>
            </nav>
        ', $html);
    }

    public function testJSONLD()
    {
        $html = Breadcrumbs::view('breadcrumbs::json-ld', 'category', $this->category)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <script type="application/ld+json">{"@context":"http:\/\/schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"@id":"http:\/\/localhost","name":"Home","image":null}},{"@type":"ListItem","position":2,"item":{"@id":"http:\/\/localhost","name":"Blog","image":null}},{"@type":"ListItem","position":3,"item":{"@id":"http:\/\/localhost\/blog\/category\/456","name":"Sample Category","image":null}}]}</script>
        ', $html);
    }
}
