<?php

namespace BreadcrumbsTests;

use Breadcrumbs;
use BreadcrumbsTests\Controllers\PostController;
use BreadcrumbsTests\Models\Post;
use Config;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Route;

class RouteBoundTest extends TestCase
{
    public function testRender()
    {
        // Home
        Route::name('home')->get('/', function () { });

        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', route('home'));
        });

        // Home > [Post]
        Route::name('post')->get('/post/{id}', function () {
            return Breadcrumbs::render();
        });

        Breadcrumbs::for('post', function ($trail, $id) {
            $post = Post::findOrFail($id);
            $trail->parent('home');
            $trail->push($post->title, route('post', $post));
        });

        $html = $this->get('/post/1')->content();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Home</a></li>
                <li class="current">Post 1</li>
            </ol>
        ', $html);
    }

    public function testGenerate()
    {
        // Home
        Route::name('home')->get('/', function () { });

        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Home', route('home'));
        });

        // Home > [Post]
        Route::name('post')->get('/post/{id}', function () use (&$breadcrumbs) {
            $breadcrumbs = Breadcrumbs::generate();
        });

        Breadcrumbs::for('post', function ($trail, $id) {
            $post = Post::findOrFail($id);
            $trail->parent('home');
            $trail->push($post->title, route('post', $post));
        });

        $this->get('/post/1');

        $this->assertCount(2, $breadcrumbs);

        $this->assertSame('Home', $breadcrumbs[0]->title);
        $this->assertSame('http://localhost', $breadcrumbs[0]->url);

        $this->assertSame('Post 1', $breadcrumbs[1]->title);
        $this->assertSame('http://localhost/post/1', $breadcrumbs[1]->url);
    }

    public function testView()
    {
        // Home
        Route::name('home')->get('/', function () { });

        Breadcrumbs::for('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', route('home'));
        });

        // Home > [Post]
        Route::name('post')->get('/post/{id}', function () {
            return Breadcrumbs::view('breadcrumbs2');
        });

        Breadcrumbs::for('post', function ($breadcrumbs, $id) {
            $post = Post::findOrFail($id);
            $breadcrumbs->parent('home');
            $breadcrumbs->push($post->title, route('post', $post));
        });

        $html = $this->get('/post/1')->content();

        $this->assertXmlStringEqualsXmlString('
            <ul>
                <li><a href="http://localhost">Home</a></li>
                <li class="current">Post 1</li>
            </ul>
        ', $html);
    }

    public function testExists()
    {
        // Exists
        Breadcrumbs::for('exists', function () { });

        Route::name('exists')->get('/exists', function () use (&$exists1) {
            $exists1 = Breadcrumbs::exists();
        });

        $this->get('/exists');
        $this->assertTrue($exists1);

        // Doesn't exist
        Route::name('doesnt-exist')->get('/doesnt-exist', function () use (&$exists2) {
            $exists2 = Breadcrumbs::exists();
        });

        $this->get('/doesnt-exist');
        $this->assertFalse($exists2);

        // Unnamed
        Route::get('/unnamed', function () use (&$exists3) {
            $exists3 = Breadcrumbs::exists();
        });

        $this->get('/unnamed');
        $this->assertFalse($exists3);
    }

    public function testError404()
    {
        Route::name('home')->get('/', function () { });

        Breadcrumbs::for('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', route('home'));
        });

        Breadcrumbs::for('errors.404', function ($breadcrumbs) {
            $breadcrumbs->parent('home');
            $breadcrumbs->push('Not Found');
        });

        $html = $this->get('/this-does-not-exist')->content();

        $this->assertXmlStringEqualsXmlString('
            <nav>
                <ol>
                    <li><a href="http://localhost">Home</a></li>
                    <li class="current">Not Found</li>
                </ol>
            </nav>
        ', $html);
    }

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\InvalidBreadcrumbException
     * @expectedExceptionMessage Breadcrumb not found with name "home"
     */
    public function testMissingBreadcrumbException()
    {
        Route::name('home')->get('/', function () {
            return Breadcrumbs::render();
        });

        throw $this->get('/')->exception;
    }

    public function testMissingBreadcrumbExceptionDisabled()
    {
        Config::set('breadcrumbs.missing-route-bound-breadcrumb-exception', false);

        Route::name('home')->get('/', function () {
            return Breadcrumbs::render();
        });

        $html = $this->get('/')->content();

        $this->assertXmlStringEqualsXmlString('
            <p>No breadcrumbs</p>
        ', $html);
    }

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException
     * @expectedExceptionMessage The current route (GET /blog) is not named
     */
    public function testUnnamedRouteException()
    {
        Route::get('/blog', function () {
            return Breadcrumbs::render();
        });

        throw $this->get('/blog')->exception;
    }

    /**
     * @expectedException \DaveJamesMiller\Breadcrumbs\Exceptions\UnnamedRouteException
     * @expectedExceptionMessage The current route (GET /) is not named
     */
    public function testUnnamedHomeRouteException()
    {
        // Make sure the message is "GET /" not "GET //"
        Route::get('/', function () {
            return Breadcrumbs::render();
        });

        throw $this->get('/')->exception;
    }

    public function testUnnamedRouteExceptionDisabled()
    {
        Config::set('breadcrumbs.unnamed-route-exception', false);

        Route::get('/', function () {
            return Breadcrumbs::render();
        });

        $html = $this->get('/')->content();

        $this->assertXmlStringEqualsXmlString('
            <p>No breadcrumbs</p>
        ', $html);
    }

    public function testExplicitModelBinding()
    {
        // Home
        Route::name('home')->get('/', function () { });

        Breadcrumbs::for('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', route('home'));
        });

        // Home > [Post]
        Route::model('post', Post::class);

        Route::name('post')->middleware(SubstituteBindings::class)->get('/post/{post}', function ($post) {
            return Breadcrumbs::render();
        });

        Breadcrumbs::for('post', function ($breadcrumbs, $post) {
            $breadcrumbs->parent('home');
            $breadcrumbs->push($post->title, route('post', $post));
        });

        $html = $this->get('/post/1')->content();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Home</a></li>
                <li class="current">Post 1</li>
            </ol>
        ', $html);
    }

    public function testImplicitModelBinding()
    {
        // Home
        Route::name('home')->get('/', function () { });

        Breadcrumbs::for('home', function ($breadcrumbs) {
            $breadcrumbs->push('Home', route('home'));
        });

        // Home > [Post]
        Route::name('post')->middleware(SubstituteBindings::class)->get('/post/{post}', function (Post $post) {
            return Breadcrumbs::render();
        });

        Breadcrumbs::for('post', function ($breadcrumbs, $post) {
            $breadcrumbs->parent('home');
            $breadcrumbs->push($post->title, route('post', $post));
        });

        $html = $this->get('/post/1')->content();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Home</a></li>
                <li class="current">Post 1</li>
            </ol>
        ', $html);
    }

    public function testResourcefulControllers()
    {
        Route::middleware(SubstituteBindings::class)->resource('post', PostController::class);

        // Posts
        Breadcrumbs::for('post.index', function ($breadcrumbs) {
            $breadcrumbs->push('Posts', route('post.index'));
        });

        // Posts > Upload Post
        Breadcrumbs::for('post.create', function ($breadcrumbs) {
            $breadcrumbs->parent('post.index');
            $breadcrumbs->push('New Post', route('post.create'));
        });

        // Posts > [Post Name]
        Breadcrumbs::for('post.show', function ($breadcrumbs, Post $post) {
            $breadcrumbs->parent('post.index');
            $breadcrumbs->push($post->title, route('post.show', $post->id));
        });

        // Posts > [Post Name] > Edit Post
        Breadcrumbs::for('post.edit', function ($breadcrumbs, Post $post) {
            $breadcrumbs->parent('post.show', $post);
            $breadcrumbs->push('Edit Post', route('post.edit', $post->id));
        });

        $html = $this->get('/post/1/edit')->content();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost/post">Posts</a></li>
                <li><a href="http://localhost/post/1">Post 1</a></li>
                <li class="current">Edit Post</li>
            </ol>
        ', $html);
    }
}
