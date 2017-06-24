 Laravel Breadcrumbs
================================================================================

[![Latest Stable Version](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/v/stable?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Total Downloads](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/downloads?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Monthly Downloads](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/d/monthly?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Latest Unstable Version](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/v/unstable?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![License](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/license?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)

A simple Laravel-style way to create breadcrumbs in [Laravel](https://laravel.com/).


 Table of Contents
--------------------------------------------------------------------------------

- [Compatibility Chart](#compatibility-chart)
- [Getting Started](#getting-started)
- [Defining Breadcrumbs](#defining-breadcrumbs)
- [Custom Templates](#custom-templates)
- [Outputting Breadcrumbs](#outputting-breadcrumbs)
- [Route-Bound Breadcrumbs](#route-bound-breadcrumbs)
- [Advanced Usage](#advanced-usage)
- [API Reference](#api-reference)
- [Changelog](#changelog)
- [Technical Support](#technical-support)
- [Bug Reports](#bug-reports)
- [Contributing](#contributing)
- [License](#license)


 Compatibility Chart
--------------------------------------------------------------------------------

| Laravel Breadcrumbs                                                    | Laravel   | PHP  |
|------------------------------------------------------------------------|-----------|------|
| 3.0.2+                                                                 | 5.0 – 5.4 | 5.4+ |
| 3.0.1                                                                  | 5.0 – 5.3 | 5.4+ |
| 3.0.0                                                                  | 5.0 – 5.2 | 5.4+ |
| [2.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.x) | 4.0 – 4.2 | 5.3+ |


 Getting Started
--------------------------------------------------------------------------------

### 1. Install Laravel Breadcrumbs

#### Install with Composer

Run this at the command line:

```bash
composer require davejamesmiller/laravel-breadcrumbs
```

This will both update `composer.json` and install the package into the `vendor/` directory.


#### Add to `config/app.php`

Add the service provider to `providers`:

```php
'providers' => [
    // ...
    DaveJamesMiller\Breadcrumbs\ServiceProvider::class,
],
```

And add the facade to `aliases`:

```php
'aliases' => [
    // ...
    'Breadcrumbs' => DaveJamesMiller\Breadcrumbs\Facade::class,
],
```

### 2. Define your breadcrumbs

Create a file called `routes/breadcrumbs.php` that looks like this:

```php
<?php

// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('home'));
});

// Home > About
Breadcrumbs::register('about', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::register('blog', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::register('category', function($breadcrumbs, $category)
{
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Page]
Breadcrumbs::register('page', function($breadcrumbs, $page)
{
    $breadcrumbs->parent('category', $page->category);
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```

See the [Defining Breadcrumbs](#defining-breadcrumbs) section for more details.


### 3. Choose a template

By default a [Bootstrap](http://getbootstrap.com/components/#breadcrumbs)-compatible ordered list will be rendered, so if you're using Bootstrap 3 you can skip this step.

First initialise the config file by running this command:

```bash
php artisan vendor:publish
```

Then open `config/breadcrumbs.php` and edit this line:

```php
'view' => 'breadcrumbs::bootstrap3',
```

The possible values are:

- [Bootstrap 3](http://getbootstrap.com/components/#breadcrumbs): `breadcrumbs::bootstrap3`
- [Bootstrap 2](http://getbootstrap.com/2.3.2/components.html#breadcrumbs): `breadcrumbs::bootstrap2`
- The path to a custom view: e.g. `_partials/breadcrumbs`

See the [Custom Templates](#custom-templates) section for more details.


### 4. Output the breadcrumbs

Finally, call `Breadcrumbs::render()` in the view template for each page, passing it the name of the breadcrumb to use and any additional parameters – for example:

```html+php
{!! Breadcrumbs::render('home') !!}

{!! Breadcrumbs::render('category', $category) !!}
```

See the [Outputting Breadcrumbs](#outputting-breadcrumbs) section for other output options, and see [Route-Bound Breadcrumbs](#route-bound-breadcrumbs) for a way to link breadcrumb names to route names automatically.


 Defining Breadcrumbs
--------------------------------------------------------------------------------

Breadcrumbs will usually correspond to actions or types of page. For each breadcrumb you specify a name, the breadcrumb title and the URL to link it to. Since these are likely to change dynamically, you do this in a closure, and you pass any variables you need into the closure.

The following examples should make it clear:

### Static pages

The most simple breadcrumb is probably going to be your homepage, which will look something like this:

```php
Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});
```

As you can see, you simply call `$breadcrumbs->push($title, $url)` inside the closure.

For generating the URL, you can use any of the standard Laravel URL-generation methods, including:

- `url('path/to/route')` (`URL::to()`)
- `secure_url('path/to/route')`
- `route('routename')` or `route('routename', 'param')` or `route('routename', ['param1', 'param2'])` (`URL::route()`)
- ``action('controller@action')`` (``URL::action()``)
- Or just pass a string URL (`'http://www.example.com/'`)

This example would be rendered like this:

> Home

### Parent links

This is another static page, but this has a parent link before it:

```php
Breadcrumbs::register('blog', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});
```

It works by calling the closure for the `home` breadcrumb defined above.

It would be rendered like this:

> [Home](#) / Blog

Note that the default template does not create a link for the last breadcrumb (the one for the current page), even when a URL is specified. You can override this by creating your own template – see [Custom Templates](#custom-templates) for more details.


### Dynamic titles and links

This is a dynamically generated page pulled from the database:

```php
Breadcrumbs::register('page', function($breadcrumbs, $page) {
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```

The `$page` variable would simply be passed in from the view:

```html+php
{!! Breadcrumbs::render('page', $page) !!}
```

It would be rendered like this:

> [Home](#) / [Blog](#) / Page Title

**Tip:** You can pass multiple parameters if necessary.


### Nested categories

Finally if you have nested categories or other special requirements, you can call `$breadcrumbs->push()` multiple times:

```php
Breadcrumbs::register('category', function($breadcrumbs, $category) {
    $breadcrumbs->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $breadcrumbs->push($ancestor->title, route('category', $ancestor->id));
    }

    $breadcrumbs->push($category->title, route('category', $category->id));
});
```

Alternatively you could make a recursive function such as this:

```php
Breadcrumbs::register('category', function($breadcrumbs, $category) {
    if ($category->parent)
        $breadcrumbs->parent('category', $category->parent);
    else
        $breadcrumbs->parent('blog');

    $breadcrumbs->push($category->title, route('category', $category->slug));
});
```

Both would be rendered like this:

> [Home](#) / [Blog](#) / [Grandparent Category](#) / [Parent Category](#) / Category Title


 Custom Templates
--------------------------------------------------------------------------------

### Create a view

To customise the HTML, create your own view file (e.g. `resources/views/_partials/breadcrumbs.blade.php`) like this:

```html+php
@if ($breadcrumbs)
    <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="active">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ul>
@endif
```

(See the [views/ directory](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/master/views) for the built-in templates.)


#### View data

The view will receive an array called `$breadcrumbs`.

Each breadcrumb is an object with the following keys:

- `title` – The breadcrumb title (see :doc:`defining`)
- `url` – The breadcrumb URL (see :doc:`defining`), or `null` if none was given
- `first` – `true` for the first breadcrumb (top level), `false` otherwise
- `last` – `true` for the last breadcrumb (current page), `false` otherwise
- Plus additional keys for each item in `$data` (see [Custom data](#custom-data))


### Update the config

Then update your config file (`config/breadcrumbs.php`) with the custom view name, e.g.:

```php
// resources/views/_partials/breadcrumbs.blade.php
'view' => '_partials/breadcrumbs',
```


 Outputting Breadcrumbs
--------------------------------------------------------------------------------

Call `Breadcrumbs::render()` in the view template for each page, passing it the name of the breadcrumb to use and any additional parameters.

### With Blade

In the page (e.g. `resources/views/home.blade.php`):

```html+php
{!! Breadcrumbs::render('home') !!}
```

Or with a parameter:

```html+php
{!! Breadcrumbs::render('category', $category) !!}
```

### With Blade layouts and @section

In the page (e.g. `resources/views/home.blade.php`):

```html+php
@extends('layout.name')

@section('breadcrumbs', Breadcrumbs::render('home'))
```

In the layout (e.g. `resources/views/app.blade.php`):

```html+php
@yield('breadcrumbs')
```

### Pure PHP (without Blade)

In the page (e.g. `resources/views/home.php`):

```html+php
<?= Breadcrumbs::render('home') ?>
```

Or use the long-hand syntax if you prefer:

```html+php
<?php echo Breadcrumbs::render('home') ?>
```


 Route-Bound Breadcrumbs
--------------------------------------------------------------------------------

In normal usage you must call `Breadcrumbs::render($name, $params...)` to render the breadcrumbs on every page. If you prefer, you can name your breadcrumbs the same as your routes and avoid this duplication.

### Setup

#### Name your routes

Make sure each of your routes has a name. For example (`routes/web.php`):

```php
// Home
Route::get('/', 'HomeController@index')->name('home');

// Home > [Page]
Route::get('/page/{id}', 'PageController@show')->name('page');
```

For more details see [Named Routes](https://laravel.com/docs/5.3/routing#named-routes) in the Laravel documentation.


#### Name your breadcrumbs to match

For each route, create a breadcrumb with the same name. For example (`routes/breadcrumbs.php`):

```php
// Home
Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

// Home > [Page]
Breadcrumbs::register('page', function($breadcrumbs, $id)
{
    $page = Page::findOrFail($id);
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```


#### Output breadcrumbs in your layout

Call `Breadcrumbs::render()` with no parameters in your layout file (e.g. `resources/views/app.blade.php`):

```html+php
{!! Breadcrumbs::render() !!}
```

This will automatically output breadcrumbs corresponding to the current route.

It will throw an exception if the breadcrumb doesn't exist, to remind you to create one. To prevent this behaviour, change it to:

```html+php
{!! Breadcrumbs::renderIfExists() !!}
```


### Route model binding

Laravel Breadcrumbs uses the same model binding as the controller. For example:

```php
// routes/web.php
Route::model('page', 'Page');
Route::get('/page/{page}', ['uses' => 'PageController@show', 'as' => 'page']);
```

```php
// app/Http/Controllers/PageController.php
class PageController extends Controller
{
    public function show($page)
    {
        return view('page/show', ['page' => $page]);
    }
}
```

```php
// routes/breadcrumbs.php
Breadcrumbs::register('page', function($breadcrumbs, $page)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```

This makes your code less verbose and more efficient by only loading the page from the database once.

For more details see [Route Model Binding](https://laravel.com/docs/5.3/routing#route-model-binding) in the Laravel documentation.


### Resourceful controllers

Laravel automatically creates route names for resourceful controllers, e.g. `photo.index`, which you can use when defining your breadcrumbs. For example:

```php
// routes/web.php
Route::resource('photo', 'PhotoController');
```

```
$ php artisan route:list
+--------+----------+--------------------+---------------+-------------------------+------------+
| Domain | Method   | URI                | Name          | Action                  | Middleware |
+--------+----------+--------------------+---------------+-------------------------+------------+
|        | GET|HEAD | photo              | photo.index   | PhotoController@index   |            |
|        | GET|HEAD | photo/create       | photo.create  | PhotoController@create  |            |
|        | POST     | photo              | photo.store   | PhotoController@store   |            |
|        | GET|HEAD | photo/{photo}      | photo.show    | PhotoController@show    |            |
|        | GET|HEAD | photo/{photo}/edit | photo.edit    | PhotoController@edit    |            |
|        | PUT      | photo/{photo}      | photo.update  | PhotoController@update  |            |
|        | PATCH    | photo/{photo}      |               | PhotoController@update  |            |
|        | DELETE   | photo/{photo}      | photo.destroy | PhotoController@destroy |            |
+--------+----------+--------------------+---------------+-------------------------+------------+
```

```php
// routes/breadcrumbs.php

// Photos
Breadcrumbs::register('photo.index', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Photos', route('photo.index'));
});

// Photos > Upload Photo
Breadcrumbs::register('photo.create', function($breadcrumbs)
{
    $breadcrumbs->parent('photo.index');
    $breadcrumbs->push('Upload Photo', route('photo.create'));
});

// Photos > [Photo Name]
Breadcrumbs::register('photo.show', function($breadcrumbs, $photo)
{
    $breadcrumbs->parent('photo.index');
    $breadcrumbs->push($photo->title, route('photo.show', $photo->id));
});

// Photos > [Photo Name] > Edit Photo
Breadcrumbs::register('photo.edit', function($breadcrumbs, $photo)
{
    $breadcrumbs->parent('photo.show', $photo);
    $breadcrumbs->push('Edit Photo', route('photo.edit', $photo->id));
});
```

For more details see [Resource Controllers](https://laravel.com/docs/5.3/controllers#resource-controllers) in the Laravel documentation.


### Implicit controllers

To use implicit controllers, you must specify names for each route. For example:

```php
// routes/web.php
Route::controller('auth', 'Auth\AuthController', [
    'getRegister' => 'auth.register',
    'getLogin'    => 'auth.login',
]);
```

(You don't need to provide route names for actions that redirect and never display a view – e.g. most POST views.)

For more details see [Implicit Controllers](https://laravel.com/docs/5.1/controllers#implicit-controllers) in the Laravel documentation.


 Advanced Usage
--------------------------------------------------------------------------------

### Breadcrumbs with no URL

The second parameter to `push()` is optional, so if you want a breadcrumb with no URL you can do so:

```php
$breadcrumbs->push('Sample');
```

The `$breadcrumb->url` value will be `null`.

The default Twitter Bootstrap templates provided render this with a CSS class of "active", the same as the last breadcrumb, because otherwise they default to black text not grey which doesn't look right.


### Custom data

The `push()` method accepts an optional third parameter, `$data` – an array of arbitrary data to be passed to the breadcrumb, which you can use in your custom template. For example, if you wanted each breadcrumb to have an icon, you could do:

```php
$breadcrumbs->push('Home', '/', ['icon' => 'home.png']);
```

The `$data` array's entries will be merged into the breadcrumb as properties, so you would access the icon as ``$breadcrumb->icon`` in your template, like this:

```html+php
<li><a href="{{ $breadcrumb->url }}">
    <img src="/images/icons/{{ $breadcrumb->icon }}">
    {{ $breadcrumb->title }}
</a></li>
```

Do not use the following keys in your data array, as they will be overwritten: `title`, `url`, `first`, `last`.


### Defining breadcrumbs in a different file

If you don't want to use `routes/breadcrumbs.php` (or `app/Http/breadcrumbs.php` in Laravel 5.2 and below), you can create a custom service provider to use instead of `DaveJamesMiller\Breadcrumbs\ServiceProvider` and override the `registerBreadcrumbs()` method:

```php
<?php

namespace App\Providers;

use DaveJamesMiller\Breadcrumbs\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function registerBreadcrumbs()
    {
        require base_path('path/to/breadcrumbs.php');
    }
}
```

If you are creating your own package, simply load them from your service provider's `boot()` method:

```php
class MyServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        if (class_exists('Breadcrumbs'))
            require __DIR__ . '/breadcrumbs.php';
    }
}
```


### Switching views dynamically

You can change the view at runtime by calling:

```php
Breadcrumbs::setView('view.name');
```

Or you can call `Breadcrumbs::generate()` and then load the view manually:

```html+php
@include('_partials/breadcrumbs2', ['breadcrumbs' => Breadcrumbs::generate('category', $category)])
```


### Overriding the "current" route

If you call `Breadcrumbs::render()` or `Breadcrumbs::generate()` with no parameters, it will use the current route name and parameters by default (as returned by Laravel's `Route::current()` method).

You can override this by calling `Breadcrumbs::setCurrentRoute($name, $param1, $param2...)` or `Breadcrumbs::setCurrentRouteArray($name, $params)`.


### Passing an array of parameters

If the breadcrumb requires multiple parameters, you would normally pass them like this:

```php
Breadcrumbs::render('name', $param1, $param2, $param3);
Breadcrumbs::generate('name', $param1, $param2, $param3);
$breadcrumbs->parent('name', $param1, $param2, $param3);
```

If you want to pass an array of parameters instead you can use these methods:

```php
Breadcrumbs::renderArray('name', $params);
Breadcrumbs::generateArray('name', $params);
$breadcrumbs->parentArray('name', $params);
```


### Checking if a breadcrumb exists

By default an exception will be thrown if the breadcrumb doesn't exist, so you know to add it. If you want suppress this you can call the following methods instead:

- `Breadcrumbs::renderIfExists()` (returns an empty string)
- `Breadcrumbs::renderIfExistsArray()` (returns an empty string) (was `renderArrayIfExists` before 3.0.0)
- `Breadcrumbs::generateIfExists()` (returns an empty array)
- `Breadcrumbs::generateIfExistsArray()` (returns an empty array) (was `generateArrayIfExists` before 3.0.0)

Alternatively you can call `Breadcrumbs::exists('name')`, which returns a boolean.


 API Reference
--------------------------------------------------------------------------------

### `Breadcrumbs` Facade

| Method                                               | Returns   | Added in |
|------------------------------------------------------|-----------|----------|
| `Breadcrumbs::register($name, $callback)`            | *(none)*  | 1.0.0    |
| `Breadcrumbs::exists()`                              | boolean   | 2.2.0    |
| `Breadcrumbs::exists($name)`                         | boolean   | 2.2.0    |
| `Breadcrumbs::generate()`                            | array     | 2.2.3    |
| `Breadcrumbs::generate($name)`                       | array     | 1.0.0    |
| `Breadcrumbs::generate($name, $param1, ...)`         | array     | 1.0.0    |
| `Breadcrumbs::generateArray($name, $params)`         | array     | 2.0.0    |
| `Breadcrumbs::generateIfExists()`                    | array     | 2.2.0    |
| `Breadcrumbs::generateIfExists($name)`               | array     | 2.2.0    |
| `Breadcrumbs::generateIfExists($name, $param1, ...)` | array     | 2.2.0    |
| `Breadcrumbs::generateIfExistsArray($name, $params)` | array     | 3.0.0    |
| `Breadcrumbs::render()`                              | string    | 2.2.0    |
| `Breadcrumbs::render($name)`                         | string    | 1.0.0    |
| `Breadcrumbs::render($name, $param1, ...)`           | string    | 1.0.0    |
| `Breadcrumbs::renderArray($name, $params)`           | string    | 2.0.0    |
| `Breadcrumbs::renderIfExists()`                      | string    | 2.2.0    |
| `Breadcrumbs::renderIfExists($name)`                 | string    | 2.2.0    |
| `Breadcrumbs::renderIfExists($name, $param1, ...)`   | string    | 2.2.0    |
| `Breadcrumbs::renderIfExistsArray($name, $params)`   | string    | 3.0.0    |
| `Breadcrumbs::setCurrentRoute($name)`                | *(none)*  | 2.2.0    |
| `Breadcrumbs::setCurrentRoute($name, $param1, ...)`  | *(none)*  | 2.2.0    |
| `Breadcrumbs::setCurrentRouteArray($name, $params)`  | *(none)*  | 2.2.0    |
| `Breadcrumbs::clearCurrentRoute()`                   | *(none)*  | 2.2.0    |
| `Breadcrumbs::setView($view)`                        | *(none)*  | 1.0.0    |

[Source](https://github.com/davejamesmiller/laravel-breadcrumbs/blob/develop/src/Manager.php)


### Defining breadcrumbs

```php
Breadcrumbs::register('name', function($breadcrumbs, $page) {
    // ...
});
```


| Method                                               | Returns   | Added in |
|------------------------------------------------------|-----------|----------|
| `$breadcrumbs->push($title)`                         | *(none)*  | 1.0.0    |
| `$breadcrumbs->push($title, $url)`                   | *(none)*  | 1.0.0    |
| `$breadcrumbs->push($title, $url, $data)`            | *(none)*  | 2.3.0    |
| `$breadcrumbs->parent($name)`                        | *(none)*  | 1.0.0    |
| `$breadcrumbs->parent($name, $param1, ...)`          | *(none)*  | 1.0.0    |
| `$breadcrumbs->parentArray($name, $params)`          | *(none)*  | 2.0.0    |

[Source](https://github.com/davejamesmiller/laravel-breadcrumbs/blob/develop/src/Generator.php)


### In the view (template)

`$breadcrumbs` (array), contains:

| Variable                             | Type          | Added in |
|--------------------------------------|---------------|----------|
| `$breadcrumb->title`                 | string        | 1.0.0    |
| `$breadcrumb->url`                   | string / null | 1.0.0    |
| `$breadcrumb->first`                 | boolean       | 1.0.0    |
| `$breadcrumb->last`                  | boolean       | 1.0.0    |
| `$breadcrumb->custom_attribute_name` | mixed         | 2.3.0    |


 Changelog
--------------------------------------------------------------------------------

*Laravel Breadcrumbs uses [Semantic Versioning](http://semver.org/).*


### [v3.0.3](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.0.3) (24 Jun 2017)

- Fix exception when using `renderIfExists()` (and related methods) with an unnamed route
  ([#133](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/133))
- Convert docs back to Markdown


### [v3.0.2](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.0.2) (30 Jan 2017)

- Laravel 5.4 support


### [v3.0.1](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.0.1) (28 Aug 2016)

- Laravel 5.3 support

#### Upgrading from Laravel 5.2 to 5.3

- Upgrade Laravel Breadcrumbs to 3.0.1 (or above)
- Move `app/Http/breadcrumbs.php` to `routes/breadcrumbs.php` (optional but recommended)


### [v3.0.0](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.0.0) (8 Feb 2015)

- Add Laravel 5 support
  ([#62](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/62))
- Change view namespace from `laravel-breadcrumbs::` to `breadcrumbs::`
- Change Bootstrap 3 template from `<ul>` to `<ol>` to match the [documentation](http://getbootstrap.com/components/#breadcrumbs)
- Move documentation from GitHub (Markdown) to [Read The Docs](https://readthedocs.org/) (reStructuredText/[Sphinx](http://sphinx-doc.org/))
- Greatly improve unit & integration tests (largely thanks to [Testbench](https://github.com/orchestral/testbench))
- Fix issue that prevented non-deferred service providers referencing Breadcrumbs by making Breadcrumbs non-deferred also
  ([#39](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/39))
- Rename `generateArrayIfExists()` to `generateIfExistsArray()`
- Rename `renderArrayIfExists()` to `renderIfExistsArray()`
- Remove `$breadcrumbs->get()` and `$breadcrumbs->set()` methods from Generator class (they were never used nor documented)
- Remove `Breadcrumbs::getView()`
- Switch from PSR-0 to PSR-4 file naming

#### Upgrading from 2.x to 3.x

- [Upgrade to Laravel 5](https://laravel.com/docs/5.0/upgrade#upgrade-5.0)
- Move `app/breadcrumbs.php` to `app/Http/breadcrumbs.php`
- Move `app/config/packages/davejamesmiller/laravel-breadcrumbs/config.php` to `config/breadcrumbs.php` (if used)

The following changes are optional because there are shims in place:

- In the config file, replace `laravel-breadcrumbs::` with `breadcrumbs::`
- Replace any calls to `Breadcrumbs::generateArrayIfExists()` with `Breadcrumbs::generateIfExistsArray()`
- Replace any calls to `Breadcrumbs::renderArrayIfExists()` with `Breadcrumbs::renderIfExistsArray()`

**Note:** Laravel 4 and PHP 5.3 are no longer supported – please continue to use the [2.x branch](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.x) if you use them.


### v2.x

[Changelog for 2.x and below](https://github.com/davejamesmiller/laravel-breadcrumbs/blob/2.x/CHANGELOG.md)


 Technical Support
--------------------------------------------------------------------------------

Sorry, **I don't offer free technical support** for my open source packages. If you can't get Laravel Breadcrumbs working in your application, I suggest you try posting a question on [Stack Overflow](https://stackoverflow.com/search?q=laravel+breadcrumbs). For paid support / consultancy please [email me](https://davejamesmiller.com/contact).


 Bug Reports
--------------------------------------------------------------------------------

Please note this is free software so **I don't guarantee to fix any bugs** – I will investigate if/when I have the time and motivation to do so. Don't be afraid to go into the Laravel Breadcrumbs code (`vendor/davejamesmiller/laravel-breadcrumbs/src/`), use `var_dump()` to see what's happening and fix your own problems! For paid support / consultancy please [email me](https://davejamesmiller.com/contact).


 Contributing
--------------------------------------------------------------------------------

**Bug fixes:** Please fix it and open a [pull request](https://github.com/davejamesmiller/laravel-breadcrumbs/pulls). Bonus points if you add a unit test to make sure it doesn't happen again!

**New features:** Only high value features with a clear use case and well-considered API will be accepted. They must be documented and include unit tests. I suggest you open an [issue](https://github.com/davejamesmiller/laravel-breadcrumbs/issues) to discuss the idea first.

**Documentation:** If you think the documentation can be improved in any way, please do [edit this file](https://github.com/davejamesmiller/laravel-breadcrumbs/edit/master/README.md) and make a pull request.


### Developing inside a real application

The easiest way to work on Laravel Breadcrumbs inside a real Laravel application is to tell Composer to install from source (Git) using the `--prefer-source` flag:

```bash
cd /path/to/repo
rm -rf vendor/davejamesmiller/laravel-breadcrumbs
composer install --prefer-source
```

Then:

```bash
cd vendor/davejamesmiller/laravel-breadcrumbs
git checkout -t origin/master
git checkout -b YOUR_BRANCH
# Make changes and commit them
git remote add YOUR_USERNAME git@github.com:YOUR_USERNAME/laravel-breadcrumbs
git push -u YOUR_USERNAME YOUR_BRANCH
```

Alternatively there is a [test app](https://github.com/davejamesmiller/laravel-breadcrumbs-test) that you can use.


### Unit tests

To run the unit tests, simply run:

```bash
cd /path/to/laravel-breadcrumbs
composer update
scripts/test.sh
```

#### Code coverage

To check code coverage, you will also need [Xdebug](https://xdebug.org/) installed. Run:

```bash
scripts/test-coverage.sh
```

Then open `test-coverage/index.html` to view the results. Be aware of the [edge cases](https://phpunit.de/manual/current/en/code-coverage-analysis.html#code-coverage-analysis.edge-cases) in PHPUnit that can make it not-quite-accurate.


### Using your fork in a project

To use your own fork in a project, update the `composer.json` in your main project as follows:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-breadcrumbs.git"
        }
    ],
    "require": {
        "davejamesmiller/laravel-breadcrumbs": "dev-YOUR_BRANCH"
    }
}
```

Replace `YOUR_USERNAME` with your GitHub username and `YOUR_BRANCH` with the branch name (e.g. `develop`). This tells Composer to use your repository instead of the default one.


 License
--------------------------------------------------------------------------------

*[MIT License](https://choosealicense.com/licenses/mit/)*

**Copyright © 2013-2017 Dave James Miller**

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
