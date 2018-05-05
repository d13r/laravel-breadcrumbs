<?php

namespace BreadcrumbsTests;

use Breadcrumbs;

class RecursionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // Blog
        Breadcrumbs::for('blog', function ($trail) {
            $trail->push('Blog', url('/'));
        });

        $this->category1 = (object) ['id' => 1, 'title' => 'Category 1'];
        $this->category2 = (object) ['id' => 2, 'title' => 'Category 2'];
        $this->category3 = (object) ['id' => 3, 'title' => 'Category 3'];
    }

    public function testRepeatedPush()
    {
        Breadcrumbs::for('category', function ($trail, $category) {
            $trail->parent('blog');

            foreach ($category->ancestors as $ancestor) {
                $trail->push($ancestor->title, url("category/{$ancestor->id}"));
            }

            $trail->push($category->title, url("category/{$category->id}"));
        });

        $this->category3->ancestors = [$this->category1, $this->category2];

        $html = Breadcrumbs::render('category', $this->category3)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Blog</a></li>
                <li><a href="http://localhost/category/1">Category 1</a></li>
                <li><a href="http://localhost/category/2">Category 2</a></li>
                <li class="current">Category 3</li>
            </ol>
        ', $html);
    }

    public function testRecursiveCall()
    {
        Breadcrumbs::for('category', function ($trail, $category) {
            if ($category->parent) {
                $trail->parent('category', $category->parent);
            } else {
                $trail->parent('blog');
            }

            $trail->push($category->title, url("category/{$category->id}"));
        });

        $this->category1->parent = null;
        $this->category2->parent = $this->category1;
        $this->category3->parent = $this->category2;

        $html = Breadcrumbs::render('category', $this->category3)->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Blog</a></li>
                <li><a href="http://localhost/category/1">Category 1</a></li>
                <li><a href="http://localhost/category/2">Category 2</a></li>
                <li class="current">Category 3</li>
            </ol>
        ', $html);
    }
}
