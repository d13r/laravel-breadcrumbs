<?php


namespace App;

use BreadcrumbsTests\Models\Post;
use BreadcrumbsTests\TestCase;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsMiddleware;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RouteDeterminationTest extends TestCase
{
    public function testRender()
    {
        // Home
        Route::get('/', function () {
            return '';
        })
            ->name('home')
            ->middleware(BreadcrumbsMiddleware::class)
            ->breadcrumbs(function (BreadcrumbsGenerator $trail) {
                $trail->push('Home', route('home'));
            });


        // Home > [Post]
        Route::get('/post/{id}', function () {
            return Breadcrumbs::render();
        })
            ->name('post')
            ->middleware(BreadcrumbsMiddleware::class)
            ->breadcrumbs(function (BreadcrumbsGenerator $trail, int $id) {
                $trail->parent('home');
                $trail->push(Post::findOrFail($id)->title);
            });

        $html = $this->get('/post/1')->content();

        $this->assertXmlStringEqualsXmlString('
            <ol>
                <li><a href="http://localhost">Home</a></li>
                <li class="current">Post 1</li>
            </ol>
        ', $html);
    }

    public function testDefaultParams(){
        // Home
        Route::get('/', function (Request $request) {
            $this->assertFalse($request->route()->hasParameter(BreadcrumbsMiddleware::class));
        })
            ->name('home')
            ->middleware(BreadcrumbsMiddleware::class)
            ->breadcrumbs(function (BreadcrumbsGenerator $trail) {
                $trail->push('Home', route('home'));
            });

        $this->get('/');
    }
}
