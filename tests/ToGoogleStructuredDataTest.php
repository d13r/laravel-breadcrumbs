<?php

namespace BreadcrumbsTests;

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use LogicException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

// Breadcrumbs::register() was renamed Breadcrumbs::for() in v5.1.0 but is still supported for backwards compatibility.
// It's not officially deprecated, but may be in the future.
class ToGoogleStructuredDataTest extends TestCase
{
    protected $category, $post;
    protected function setUp(): void
    {
        parent::setUp();

        $closure = function () { throw new LogicException; };

        // Home
        Route::name('home')->get('/', $closure);

        Breadcrumbs::register('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', route('home'));
        });

        // Home > About
        Route::name('about')->get('about', $closure);

        Breadcrumbs::register('about', function ($breadcrumbs) {
            $breadcrumbs->parent('home');
            $breadcrumbs->push('About', route('about'));
        });

        // Home > Blog
        Route::name('blog')->get('blog', $closure);

        Breadcrumbs::register('blog', function ($breadcrumbs) {
            $breadcrumbs->parent('home');
            $breadcrumbs->push('Blog', route('blog'));
        });

        // Home > Blog > [Category]
        Route::name('category')->get('blog/category/{category}', $closure);

        Breadcrumbs::register('category', function ($breadcrumbs, $category) {
            $breadcrumbs->parent('blog');
            $breadcrumbs->push($category->title, route('category', $category->id));
        });

        // Home > Blog > [Category] > [Post]
        Route::name('post')->get('blog/post/{post}', $closure);

        Breadcrumbs::register('post', function ($breadcrumbs, $post) {
            $breadcrumbs->parent('category', $post->category);
            $breadcrumbs->push($post->title, route('post', $post->id));
        });

        $this->category = (object) [
            'id'    => 456,
            'title' => 'Sample Category',
        ];

        $this->post = (object) [
            'id'       => 123,
            'title'    => 'Sample Post',
            'category' => $this->category,
        ];
    }

    public function testGenerate()
    {
        $googleStructuredData = Breadcrumbs::toGoogleStructuredData('post', $this->post);

        $this->assertSame('https://schema.org', $googleStructuredData['@context']);

        $this->assertSame('BreadcrumbList', $googleStructuredData['@type']);

        $this->assertCount(4, $googleStructuredData['itemListElement']);

        $this->assertSame('ListItem', $googleStructuredData['itemListElement'][0]['@type']);
        $this->assertSame('http://localhost', $googleStructuredData['itemListElement'][0]['item']);
        $this->assertSame(1, $googleStructuredData['itemListElement'][0]['position']);
        $this->assertSame('Home', $googleStructuredData['itemListElement'][0]['name']);


        $this->assertSame('ListItem', $googleStructuredData['itemListElement'][1]['@type']);
        $this->assertSame('http://localhost/blog', $googleStructuredData['itemListElement'][1]['item']);
        $this->assertSame(2, $googleStructuredData['itemListElement'][1]['position']);
        $this->assertSame('Blog', $googleStructuredData['itemListElement'][1]['name']);

        $this->assertSame('ListItem', $googleStructuredData['itemListElement'][2]['@type']);
        $this->assertSame('http://localhost/blog/category/456', $googleStructuredData['itemListElement'][2]['item']);
        $this->assertSame(3, $googleStructuredData['itemListElement'][2]['position']);
        $this->assertSame('Sample Category', $googleStructuredData['itemListElement'][2]['name']);

        $this->assertSame('ListItem', $googleStructuredData['itemListElement'][3]['@type']);
        $this->assertSame('http://localhost/blog/post/123', $googleStructuredData['itemListElement'][3]['item']);
        $this->assertSame(4, $googleStructuredData['itemListElement'][3]['position']);
        $this->assertSame('Sample Post', $googleStructuredData['itemListElement'][3]['name']);

        $this->assertInstanceOf(Collection::class, $googleStructuredData);
        $this->assertInstanceOf(Collection::class, $googleStructuredData['itemListElement']);
    }
}
