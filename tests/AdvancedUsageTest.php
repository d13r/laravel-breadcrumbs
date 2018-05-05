<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use BreadcrumbsTests\Models\Post;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Collection;
use Route;

class AdvancedUsageTest extends TestCase
{
    public function testBreadcrumbsWithNoUrl()
    {
        Breadcrumbs::for('sample', function ($trail) {
            $trail->push('Sample');
        });

        $breadcrumbs = Breadcrumbs::generate('sample');

        $this->assertCount(1, $breadcrumbs);
        $this->assertSame('Sample', $breadcrumbs[0]->title);
        $this->assertNull($breadcrumbs[0]->url);
    }

    public function testCustomData()
    {
        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', '/', ['icon' => 'home.png']);
        });

        $breadcrumbs = Breadcrumbs::generate('home');

        $this->assertCount(1, $breadcrumbs);
        $this->assertSame('Home', $breadcrumbs[0]->title);
        $this->assertSame('/', $breadcrumbs[0]->url);
        $this->assertSame('home.png', $breadcrumbs[0]->icon);
    }

    public function testBeforeAndAfterCallbacks()
    {
        Breadcrumbs::before(function ($trail) {
            $trail->push('Before');
        });

        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', route('home'));
        });

        Breadcrumbs::after(function ($trail) {
            $page = (int) request('page', 1);
            if ($page > 1) {
                $trail->push("Page $page");
            }
        });

        Route::name('home')->get('/', function () {
            return Breadcrumbs::render('home');
        });

        $html = $this->get('/?page=2')->content();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li>Before</li>
                <li><a href="http://localhost">Home</a></li>
                <li class="current">Page 2</li>
            </ol>
        ', $html);
    }

    public function testCurrentPageBreadcrumb()
    {
        Route::name('home')->get('/', function () { });

        Route::name('post')->middleware(SubstituteBindings::class)->get('/post/{post}', function (Post $post) {
            return Breadcrumbs::current()->title;
        });

        Breadcrumbs::for('post', function ($trail, $post) {
            $trail->push('Home', route('home'));
            $trail->push($post->title, route('post', $post));
            $trail->push('Page 2', null, ['current' => false]);
        });

        $html = $this->get('/post/1')->content();

        $this->assertSame('Post 1', $html);
    }

    public function testGenerateCollection()
    {
        Route::name('home')->get('/', function () { });
        Route::name('post')->get('/post/{post}', function () { });

        Breadcrumbs::for('post', function ($trail, $id) {
            $trail->push('Home', route('home'));
            $trail->push("Post $id", route('post', $id));
            $trail->push('Page 2', null, ['current' => false]);
        });

        $breadcrumbs = Breadcrumbs::generate('post', 1)->where('current', '!==', false);

        $this->assertInstanceOf(Collection::class, $breadcrumbs);
        $this->assertSame('Post 1', $breadcrumbs->last()->title);
    }

    public function testMacro()
    {
        Route::name('home')->get('/', function () { });

        Route::name('post')->middleware(SubstituteBindings::class)->get('/post/{post}', function (Post $post) {
            return Breadcrumbs::pageTitle();
        });

        Breadcrumbs::for('post', function ($trail, $post) {
            $trail->push('Home', route('home'));
            $trail->push($post->title, route('post', $post));
            $trail->push('Page 2', null, ['current' => false]);
        });

        Breadcrumbs::macro('pageTitle', function () {
            $title = ($breadcrumb = Breadcrumbs::current()) ? "{$breadcrumb->title} – " : '';

            if (($page = (int) request('page')) > 1) {
                $title .= "Page $page – ";
            }

            return $title . 'Demo App';
        });

        $html = $this->get('/post/1?page=2')->content();

        $this->assertSame('Post 1 – Page 2 – Demo App', $html);
    }

    public function testSetCurrentRoute()
    {
        Breadcrumbs::for('sample', function ($trail) {
            $trail->push("Sample");
        });

        Breadcrumbs::setCurrentRoute('sample');

        $html = Breadcrumbs::render()->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Sample</li>
            </ol>
        ', $html);
    }

    public function testSetCurrentRouteWithParams()
    {
        Breadcrumbs::for('sample', function ($trail, $a, $b) {
            $trail->push("Sample $a, $b");
        });

        Breadcrumbs::setCurrentRoute('sample', 1, 2);

        $html = Breadcrumbs::render()->toHtml();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li class="current">Sample 1, 2</li>
            </ol>
        ', $html);
    }

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException
     */
    public function testClearCurrentRoute()
    {
        Breadcrumbs::for('sample', function ($trail, $a, $b) {
            $trail->push("Sample $a, $b");
        });

        Breadcrumbs::setCurrentRoute('sample', 1, 2);
        Breadcrumbs::clearCurrentRoute();

        Breadcrumbs::render();
    }
}
