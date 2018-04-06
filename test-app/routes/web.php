<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::bind('category', function ($id) {
    return new App\Category($id);
});

Route::bind('post', function ($id) {
    return new App\Post($id);
});

Route::view('/', 'home')->name('home');
Route::view('/blog', 'blog')->name('blog');

Route::name('category')->get('/category/{category}', function ($category) {
    $paginator = (new LengthAwarePaginator([], 100, 10))->setPath(Paginator::resolveCurrentPath());

    return view('category', compact('category', 'paginator'));
});

Route::name('post')->get('/post/{post}', function ($post) {
    return view('post', compact('post'));
});

Route::view('/section', 'section')->name('section');
Route::view('/bootstrap2', 'bootstrap2')->name('bootstrap2');
Route::view('/bootstrap3', 'bootstrap3')->name('bootstrap3');
Route::view('/bulma', 'bulma')->name('bulma');
Route::view('/foundation6', 'foundation6')->name('foundation6');
Route::view('/materialize', 'materialize')->name('materialize');
Route::view('/print_r', 'print_r')->name('print_r');

Route::view('/unnamed', 'unnamed');

Route::name('server-error')->get('/server-error', function () {
    abort(500);
});

Route::name('phpinfo')->get('/phpinfo', function () {
    phpinfo();
});
