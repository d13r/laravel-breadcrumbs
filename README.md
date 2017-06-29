 Laravel Breadcrumbs
================================================================================

[![Latest Stable Version](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/v/stable?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Total Downloads](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/downloads?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Monthly Downloads](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/d/monthly?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Latest Unstable Version](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/v/unstable?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![License](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/license?format=flat-square)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)

A simple [Laravel](https://laravel.com/)-style way to create breadcrumbs.


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
| 4.0.0                                                                  | 5.5       | 7.0+ |
| 3.0.2 – 3.0.3                                                          | 5.0 – 5.4 | 5.4+ |
| 3.0.1                                                                  | 5.0 – 5.3 | 5.4+ |
| 3.0.0                                                                  | 5.0 – 5.2 | 5.4+ |
| [2.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.x) | 4.0 – 4.2 | 5.3+ |


 Getting Started
--------------------------------------------------------------------------------

### 1. Install Laravel Breadcrumbs

Run this at the command line:

```bash
composer require davejamesmiller/laravel-breadcrumbs
```

This will both update `composer.json` and install the package into the `vendor/` directory.


### 2. Define your breadcrumbs

Create a file called `routes/breadcrumbs.php` that looks like this:

```php
<?php

// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

// Home > About
Breadcrumbs::register('about', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::register('blog', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Page]
Breadcrumbs::register('page', function ($breadcrumbs, $page) {
    $breadcrumbs->parent('category', $page->category);
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```

See the [Defining Breadcrumbs](#defining-breadcrumbs) section for more details.


### 3. Choose a template

By default a [Bootstrap](http://getbootstrap.com/components/#breadcrumbs)-compatible ordered list will be rendered, so if you're using Bootstrap 3 you can skip this step.

First initialise the config file by running this command:

```bash
php artisan vendor:publish --provider='DaveJamesMiller\Breadcrumbs\ServiceProvider'
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
{{ Breadcrumbs::render('home') }}

{{ Breadcrumbs::render('category', $category) }}
```

See the [Outputting Breadcrumbs](#outputting-breadcrumbs) section for other output options, and see [Route-Bound Breadcrumbs](#route-bound-breadcrumbs) for a way to link breadcrumb names to route names automatically.


 Defining Breadcrumbs
--------------------------------------------------------------------------------

Breadcrumbs will usually correspond to actions or types of page. For each breadcrumb you specify a name, the breadcrumb title and the URL to link it to. Since these are likely to change dynamically, you do this in a closure, and you pass any variables you need into the closure.

The following examples should make it clear:

### Static pages

The most simple breadcrumb is probably going to be your homepage, which will look something like this:

```php
Breadcrumbs::register('home', function ($breadcrumbs) {
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
Breadcrumbs::register('blog', function ($breadcrumbs) {
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
Breadcrumbs::register('page', function ($breadcrumbs, $page) {
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```

The `$page` variable would simply be passed in from the view:

```html+php
{{ Breadcrumbs::render('page', $page) }}
```

It would be rendered like this:

> [Home](#) / [Blog](#) / Page Title

**Tip:** You can pass multiple parameters if necessary.


### Nested categories

Finally, if you have nested categories or other special requirements, you can call `$breadcrumbs->push()` multiple times:

```php
Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $breadcrumbs->push($ancestor->title, route('category', $ancestor->id));
    }

    $breadcrumbs->push($category->title, route('category', $category->id));
});
```

Alternatively you could make a recursive function such as this:

```php
Breadcrumbs::register('category', function ($breadcrumbs, $category) {
    if ($category->parent) {
        $breadcrumbs->parent('category', $category->parent);
    } else {
        $breadcrumbs->parent('blog');
    }

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
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="active">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ol>
@endif
```

(See the [views/ directory](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/master/views) for the built-in templates.)


#### View data

The view will receive an array called `$breadcrumbs`.

Each breadcrumb is an object with the following keys:

- `title` – The breadcrumb title
- `url` – The breadcrumb URL, or `null` if none was given
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
{{ Breadcrumbs::render('home') }}
```

Or with a parameter:

```html+php
{{ Breadcrumbs::render('category', $category) }}
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

In normal usage you must call `Breadcrumbs::render($name, $params...)` to render the breadcrumbs on every page. If you prefer, you can name your breadcrumbs the same as your routes and avoid this duplication...


### Name your routes

Make sure each of your routes has a name. For example (`routes/web.php`):

```php
// Home
Route::name('home')->get('/', 'HomeController@index');

// Home > [Page]
Route::name('page')->get('/page/{id}', 'PageController@show');
```

For more details see [Named Routes](https://laravel.com/docs/5.4/routing#named-routes) in the Laravel documentation.


### Name your breadcrumbs to match

For each route, create a breadcrumb with the same name and parameters. For example (`routes/breadcrumbs.php`):

```php
// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
     $breadcrumbs->push('Home', route('home'));
});

// Home > [Page]
Breadcrumbs::register('page', function ($breadcrumbs, $id) {
    $page = Page::findOrFail($id);
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```


### Output breadcrumbs in your layout

Call `Breadcrumbs::render()` with no parameters in your layout file (e.g. `resources/views/app.blade.php`):

```html+php
{{ Breadcrumbs::render() }}
```

This will automatically output breadcrumbs corresponding to the current route.


### Route-model binding exceptions

It will throw an exception if the breadcrumb doesn't exist, to remind you to create one. To prevent this, first initialise the config file, if you haven't already:

```bash
php artisan vendor:publish --provider='DaveJamesMiller\Breadcrumbs\ServiceProvider'
```

Then open `config/breadcrumbs.php` and set this value:

```php
    'missing-route-bound-breadcrumb-exception' => false,
```

Similarly to prevent it throwing an exception if the current route doesn't have a name set this value:

```php
    'unnamed-route-exception' => false,
```


### Route model binding

Laravel Breadcrumbs uses the same model binding as the controller. For example:

```php
// routes/web.php
Route::name('page')->get('/page/{page}', 'PageController@show');
```

```php
// app/Http/Controllers/PageController.php
use App\Page;

class PageController extends Controller
{
    public function show(Page $page) // <-- Implicit model binding happens here
    {
        return view('page/show', ['page' => $page]);
    }
}
```

```php
// routes/breadcrumbs.php
Breadcrumbs::register('page', function ($breadcrumbs, $page) { // <-- The same Page model is injected here
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```

This makes your code less verbose and more efficient by only loading the page from the database once.

For more details see [Route Model Binding](https://laravel.com/docs/5.4/routing#route-model-binding) in the Laravel documentation.


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
Breadcrumbs::register('photo.index', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Photos', route('photo.index'));
});

// Photos > Upload Photo
Breadcrumbs::register('photo.create', function ($breadcrumbs) {
    $breadcrumbs->parent('photo.index');
    $breadcrumbs->push('Upload Photo', route('photo.create'));
});

// Photos > [Photo Name]
Breadcrumbs::register('photo.show', function ($breadcrumbs, $photo) {
    $breadcrumbs->parent('photo.index');
    $breadcrumbs->push($photo->title, route('photo.show', $photo->id));
});

// Photos > [Photo Name] > Edit Photo
Breadcrumbs::register('photo.edit', function ($breadcrumbs, $photo) {
    $breadcrumbs->parent('photo.show', $photo);
    $breadcrumbs->push('Edit Photo', route('photo.edit', $photo->id));
});
```

For more details see [Resource Controllers](https://laravel.com/docs/5.4/controllers#resource-controllers) in the Laravel documentation.


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

If you don't want to use `routes/breadcrumbs.php`, you can change it in the config file. First initialise the config file, if you haven't already:

```bash
php artisan vendor:publish --provider='DaveJamesMiller\Breadcrumbs\ServiceProvider'
```

Then open `config/breadcrumbs.php` and edit this line:

```php
    'files' => base_path('routes/breadcrumbs.php'),
```

It can be an absolute path, as above, or an array:

```php
    'files' => [
        base_path('breadcrumbs/admin.php'),
        base_path('breadcrumbs/frontend.php'),
    ],
```

So you can use `glob()` to automatically find files using a wildcard:

```php
    'files' => glob(base_path('breadcrumbs/*.php')),
```

Or return an empty array `[]` to disable loading.

### Defining breadcrumbs in another package

If you are creating your own package, simply load your breadcrumbs file from your service provider's `boot()` method:

```php
class MyServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        if (class_exists('Breadcrumbs')) {
            require __DIR__ . '/breadcrumbs.php';
        }
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

You can override this by calling `Breadcrumbs::setCurrentRoute($name, $param1, $param2...)`.


### Checking if a breadcrumb exists

By default an exception will be thrown if the breadcrumb doesn't exist, so you know to add it. If you want suppress this ... TODO

Alternatively you can call `Breadcrumbs::exists('name')`, which returns a boolean.


 API Reference
--------------------------------------------------------------------------------

### `Breadcrumbs` Facade

| Method                                                           | Returns   | Added in |
|------------------------------------------------------------------|-----------|----------|
| `Breadcrumbs::register(string $name, closure $callback)`         | *(none)*  | 1.0.0    |
| `Breadcrumbs::exists()`                                          | boolean   | 2.2.0    |
| `Breadcrumbs::exists(string $name)`                              | boolean   | 2.2.0    |
| `Breadcrumbs::generate()`                                        | array     | 2.2.3    |
| `Breadcrumbs::generate(string $name)`                            | array     | 1.0.0    |
| `Breadcrumbs::generate(string $name, mixed $param1, ...)`        | array     | 1.0.0    |
| `Breadcrumbs::render()`                                          | string    | 2.2.0    |
| `Breadcrumbs::render(string $name)`                              | string    | 1.0.0    |
| `Breadcrumbs::render(string $name, mixed $param1, ...)`          | string    | 1.0.0    |
| `Breadcrumbs::setCurrentRoute(string $name)`                     | *(none)*  | 2.2.0    |
| `Breadcrumbs::setCurrentRoute(string $name, mixed $param1, ...)` | *(none)*  | 2.2.0    |
| `Breadcrumbs::clearCurrentRoute()`                               | *(none)*  | 2.2.0    |
| `Breadcrumbs::setView($view)`                                    | *(none)*  | 1.0.0    |

[Source](https://github.com/davejamesmiller/laravel-breadcrumbs/blob/develop/src/Manager.php)


### Defining breadcrumbs

```php
use App\Models\Page;
use DaveJamesMiller\Breadcrumbs\Generator;

Breadcrumbs::register('name', function (Generator $breadcrumbs, Page $page) {
    // ...
});
```


| Method                                                        | Returns   | Added in |
|---------------------------------------------------------------|-----------|----------|
| `$breadcrumbs->push(string $title)`                           | *(none)*  | 1.0.0    |
| `$breadcrumbs->push(string $title, string $url)`              | *(none)*  | 1.0.0    |
| `$breadcrumbs->push(string $title, string $url, array $data)` | *(none)*  | 2.3.0    |
| `$breadcrumbs->parent(string $name)`                          | *(none)*  | 1.0.0    |
| `$breadcrumbs->parent(string $name, mixed $param1, ...)`      | *(none)*  | 1.0.0    |

[Source](https://github.com/davejamesmiller/laravel-breadcrumbs/blob/develop/src/Generator.php)


### In the view (template)

`$breadcrumbs` (array), contains:

| Variable                             | Type          | Added in |
|--------------------------------------|---------------|----------|
| `$breadcrumb->title`                 | string        | 1.0.0    |
| `$breadcrumb->url`                   | string / null | 1.0.0    |
| `$breadcrumb->custom_attribute_name` | mixed         | 2.3.0    |


 Changelog
--------------------------------------------------------------------------------

*Laravel Breadcrumbs uses [Semantic Versioning](http://semver.org/).*


### [v4.0.0](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/4.0.0) (Date TBC)

- Add Laravel 5.5 support, and drop support for Laravel 5.4 and below (future versions will target a single Laravel release to simplify testing and documentation)
- Add [package auto-discovery](https://laravel-news.com/package-auto-discovery)
- Add type hints to all methods (parameters and return value)
- Add more specific exception classes:
    - `DuplicateBreadcrumbException`
    - `InvalidBreadcrumbException`
    - `InvalidViewException`
    - `UnnamedRouteException`
- Remove `$breadcrumbs->first` and `$breadcrumbs->last` in views (use [Blade's](https://laravel.com/docs/5.4/blade#loops) `$loop->first` and `$loop->last` instead)
- Remove `Array` variants of methods – use [variadic arguments](https://php.net/manual/en/migration56.new-features.php#migration56.new-features.variadics) instead:
    - `Breadcrumbs::renderArray($page, $params)` → `Breadcrumbs::render($page, ...$params)`
    - `Breadcrumbs::generateArray($page, $params)` → `Breadcrumbs::generate($page, ...$params)`
    - `Breadcrumbs::setCurrentRouteArray($name, $params)` → `Breadcrumbs::setCurrentRoute($page, ...$params)`
    - `$breadcrumbs->parentArray($name, $params)` → `$breadcrumbs->parent($name, ...$params)`
- Remove `IfExists` variants of methods – set new config settings to `false` instead:
    - `unnamed-route-exception` – when route-bound breadcrumbs are used but the current route doesn't have a name
    - `missing-route-bound-breadcrumb-exception` – when route-bound breadcrumbs are used and the matching breadcrumb doesn't exist
    - `invalid-named-breadcrumb-exception` – when a named breadcrumbs is used doesn't exist
- Remove `app/Http/breadcrumbs.php` file loading (use `routes/breadcrumbs.php`, or change the `files` setting in the config file)
- Remove `laravel-breadcrumbs::` view prefix (use `breadcrumbs::` instead)
- Remove `$app['breadcrumbs']` container short name (use `Breadcrumbs::` facade or `DaveJamesMiller\Breadcrumbs\Manager` type hint)


### v3.x

[Changelog for v3.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.x#changelog)


### v2.x

[Changelog for v2.x and below](https://github.com/davejamesmiller/laravel-breadcrumbs/blob/2.x/CHANGELOG.md)


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
