<?php

namespace BreadcrumbsTests;

use Breadcrumbs;

class IntegrationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Breadcrumbs::register('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', '/');
        });

        Breadcrumbs::register('category', function ($breadcrumbs, $category) {
            $breadcrumbs->parent('home');
            $breadcrumbs->push($category->title, '/category/' . $category->id);
        });

        Breadcrumbs::register('post', function ($breadcrumbs, $post) {
            $breadcrumbs->parent('category', $post->category);
            $breadcrumbs->push($post->title, '/blog/' . $post->id);
        });

        $this->post = (object) [
            'id'       => 123,
            'title'    => 'Sample Post',
            'category' => (object) [
                'id'    => 456,
                'title' => 'Sample Category',
            ],
        ];
    }

    public function testGenerate()
    {
        $breadcrumbs = Breadcrumbs::generate('post', $this->post);

        $this->assertCount(3, $breadcrumbs);

        $this->assertSame('Home', $breadcrumbs[0]->title);
        $this->assertSame('/', $breadcrumbs[0]->url);

        $this->assertSame('Sample Category', $breadcrumbs[1]->title);
        $this->assertSame('/category/456', $breadcrumbs[1]->url);

        $this->assertSame('Sample Post', $breadcrumbs[2]->title);
        $this->assertSame('/blog/123', $breadcrumbs[2]->url);
    }

    public function testRender()
    {
        $html = Breadcrumbs::render('post', $this->post)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/category/456">Sample Category</a></li>
                <li class="breadcrumb-item active">Sample Post</li>
            </ol>
        ', $html);
    }
}
