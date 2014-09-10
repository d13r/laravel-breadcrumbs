# Laravel Breadcrumbs
[![Latest Stable Version](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/v/stable.png)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Total Downloads](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/downloads.png)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Build Status](https://travis-ci.org/davejamesmiller/laravel-breadcrumbs.png?branch=master)](https://travis-ci.org/davejamesmiller/laravel-breadcrumbs)
[![Coverage Status](https://coveralls.io/repos/davejamesmiller/laravel-breadcrumbs/badge.png)](https://coveralls.io/r/davejamesmiller/laravel-breadcrumbs)

A simple Laravel-style way to create breadcrumbs in
[Laravel 4](http://laravel.com/).

## Installation

### 1. Install with Composer

```bash
composer require davejamesmiller/laravel-breadcrumbs:~2.2.2
```

This will update `composer.json` and install it into the `vendor/` directory.

(See the [Packagist website](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
for a list of available version numbers and development releases.)

### 2. Add to `app/config/app.php`

```php
    'providers' => array(
        // ...
        'DaveJamesMiller\Breadcrumbs\ServiceProvider',
    ),
```

And:

```php
    'aliases' => array(
        // ...
        'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facade',
    ),
```

This registers the package with Laravel and creates an alias called
`Breadcrumbs`.

## Usage

### 1. Define breadcrumbs in `app/breadcrumbs.php`

Create a file called `app/breadcrumbs.php` to put your breadcrumbs in. This file
will be loaded automatically.

It should look something like this - see the *Defining breadcrumbs* section
below for an explanation.

```php
<?php

Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

Breadcrumbs::register('blog', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});

Breadcrumbs::register('category', function($breadcrumbs, $category) {
    $breadcrumbs->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $breadcrumbs->push($ancestor->title, route('category', $ancestor->id));
    }

    $breadcrumbs->push($category->title, route('category', $category->id));
});

Breadcrumbs::register('page', function($breadcrumbs, $page) {
    $breadcrumbs->parent('category', $page->category);
    $breadcrumbs->push($page->title, route('page', $page->id));
});
```

### 2. Choose/create a template to render the breadcrumbs

By default a
[Twitter Bootstrap v3](http://getbootstrap.com/components/#breadcrumbs)-compatible
unordered list will be rendered.

If you would like to change the template, first you need to generate a config
file by running this command:

```bash
php artisan config:publish davejamesmiller/laravel-breadcrumbs
```

Then open `app/config/packages/davejamesmiller/laravel-breadcrumbs/config.php`
and edit this line:

```php
'view' => 'laravel-breadcrumbs::bootstrap3',
```

The possible values are:

* `laravel-breadcrumbs::bootstrap3` (Twitter Bootstrap 3)
* `laravel-breadcrumbs::bootstrap2` (Twitter Bootstrap 2)
* A path to a custom template, e.g. `_partials.breadcrumbs`

#### Creating a custom template

If you want to customise the HTML, create your own view file (e.g.
`app/views/_partials/breadcrumbs.blade.php`) like this:

The view should be similar to this:

```html+php
@if ($breadcrumbs)
    <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                <li><a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a></li>
            @else
                <li class="active">{{{ $breadcrumb->title }}}</li>
            @endif
        @endforeach
    </ul>
@endif
```

As you can see above it will receive an array called `$breadcrumbs`. Each
breadcrumb is an object with the following keys:

* `title` - The title you set above
* `url` - The URL you set above
* `first` - `true` for the first breadcrumb, `false` otherwise
* `last` - `true` for the last breadcrumb, `false` otherwise

Then update your config file with the custom view name, e.g.:

```php
'view' => '_partials.breadcrumbs',
```

### 3. Output the breadcrumbs in your view

#### With Blade

Finally, call `Breadcrumbs::render()` in the view template for each page. You
can either pass the name of the breadcrumb to use (and parameters if needed):

```html+php
{{ Breadcrumbs::render('home') }}
{{ Breadcrumbs::render('category', $category) }}
```

Or in Laravel 4.1+ you can avoid the need to do this for every page by naming
your breadcrumbs the same as your routes. For example, if you have this in
`routes.php`:

```
Route::model('category', 'Category');
Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);
Route::get('/category/{category}', ['uses' => 'CategoryController@show', 'as' => 'category']);
```

And in the layout you have this:

```html+php
{{ Breadcrumbs::render() }}
```

Then on the homepage it will be the same as calling `Breadcrumbs::render('home')`
and on the category page it will be the same as calling
`Breadcrumbs::render('category', $category)`.

The key here is the `'as'` parameter must match the breadcrumb name. The
parameters passed to the breadcrumbs callback will be the same as the ones
Laravel passes to the controller (see the [Route
parameters](http://laravel.com/docs/routing#route-parameters) section of the
Laravel documentation).

#### With Blade layouts and @section

In the main page:

```html+php
@extends('layout.name')

@section('breadcrumbs', Breadcrumbs::render('category', $category))
```

In the layout:

```html+php
@yield('breadcrumbs')
```

#### Pure PHP, without Blade

```html+php
<?= Breadcrumbs::render('category', $category) ?>
```

Or the long syntax if you prefer:

```html+php
<?php echo Breadcrumbs::render('category', $category) ?>
```

## Defining breadcrumbs

Breadcrumbs will usually correspond to actions or types of page. For each
breadcrumb you specify a name, the breadcrumb title and the URL to link it to.
Since these are likely to change dynamically, you do this in a closure, and you
pass any variables you need into the closure.

The following examples should make it clear:

### Static pages

The most simple breadcrumb is probably going to be your homepage, which will
look something like this:

```php
Breadcrumbs::register('home', function($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});
```

As you can see, you simply call `$breadcrumbs->push($title, $url)` inside the
closure.

For generating the URL, you can use any of the standard Laravel URL-generation
methods, including:

* `url('path/to/route')` (`URL::to()`)
* `secure_url('path/to/route')`
* `route('routename')` (`URL::route()`)
* `action('controller@action')` (`URL::action()`)
* Or just pass a string URL (`'http://www.example.com/'`)

This example would be rendered like this:

<pre>
Home
</pre>

### Parent links

This is another static page, but this has a parent link before it:

```php
Breadcrumbs::register('blog', function($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});
```

It would be rendered like this:

<pre>
<a href="#">Home</a> / Blog
</pre>

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
{{ Breadcrumbs::render('page', $page) }}
```

It would be rendered like this:

<pre>
<a href="#">Home</a> / <a href="#">Blog</a> / Page Title
</pre>

**Tip:** You can pass multiple parameters if necessary.

### Nested categories

Finally if you have nested categories or other special requirements, you can
call `$breadcrumbs->push()` multiple times:

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

<pre>
<a href="#">Home</a> / <a href="#">Blog</a> / <a href="#">Grandparent Category</a> / <a href="#">Parent Category</a> / Category Title
</pre>

## Advanced usage

### Breadcrumbs with no URL

The second parameter to `push()` is optional, so if you want a breadcrumb with
no URL you can do so:

```php
$breadcrumbs->push('Sample');
```

The `$breadcrumb->url` value will be `null`.

The default Twitter Bootstrap templates provided render this with a CSS class of
"active", the same as the last breadcrumb, because otherwise they default to
black text not grey which doesn't look right.

### Defining breadcrumbs in a different file

If you don't want to use `app/breadcrumbs.php`, you can define them in
`routes.php`, `start/global.php`, or any other file as long as it's loaded by
Laravel.

### Switching views dynamically

You can change the view at runtime by calling:

```php
Breadcrumbs::setView('view.name');
```

If you need different views in different templates, you can call
`Breadcrumbs::generate()` to get the `$breadcrumbs` array and then load the view
manually:

```html+php
@include('_partials/breadcrumbs2', array('breadcrumbs' => Breadcrumbs::generate('category', $category)))
```

or

```html+php
{{ View::make('_partials/breadcrumbs2', array('breadcrumbs' => Breadcrumbs::generate('category', $category))) }}
```

### Overriding the "current" route

If you call `Breadcrumbs::render()` or `Breadcrumbs::generate()` with no
parameters, it will use the current route name and parameters, as returned by
Laravel's `Route::current()` method, by default.

You can override this by calling
`Breadcrumbs::setCurrentRoute($name, $param1, $param2...)` or
`Breadcrumbs::setCurrentRouteArray($name, $params)`.

### Passing an array of parameters

If the breadcrumb requires multiple parameters, you would normally pass them
like this:

```
Breadcrumbs::render('name', $param1, $param2, $param3);
Breadcrumbs::generate('name', $param1, $param2, $param3);
$breadcrumbs->parent('name', $param1, $param2, $param3);
```

If you want to pass an array of parameters instead you can use these methods:

```
Breadcrumbs::renderArray('name', $params);
Breadcrumbs::generateArray('name', $params);
$breadcrumbs->parentArray('name', $params);
```

### Checking if a breadcrumb exists

By default an exception will be thrown if the breadcrumb doesn't exist, so you
know to add it. If you want suppress this you can call the following methods
instead:

* `Breadcrumbs::renderIfExists()` (returns an empty string)
* `Breadcrumbs::renderArrayIfExists()` (returns an empty string)
* `Breadcrumbs::generateIfExists()` (returns an empty array)
* `Breadcrumbs::generateArrayIfExists()` (returns an empty array)

Alternatively you can call `Breadcrumbs::exists('name')`, which returns a
boolean.

## API Reference

### Facade

* `Breadcrumbs::register($name, $callback)`
* `Breadcrumbs::exists()` (returns boolean)
* `Breadcrumbs::exists($name)` (returns boolean)
* `Breadcrumbs::generate()` (returns array)
* `Breadcrumbs::generate($name)` (returns array)
* `Breadcrumbs::generate($name, $param1, ...)` (returns array)
* `Breadcrumbs::generateArray($name, $params)` (returns array)
* `Breadcrumbs::generateIfExists()` (returns array)
* `Breadcrumbs::generateIfExists($name)` (returns array)
* `Breadcrumbs::generateIfExists($name, $param1, ...)` (returns array)
* `Breadcrumbs::generateArrayIfExists($name, $params)` (returns array)
* `Breadcrumbs::render()` (returns string)
* `Breadcrumbs::render($name)` (returns string)
* `Breadcrumbs::render($name, $param1, ...)` (returns string)
* `Breadcrumbs::renderArray($name, $params)` (returns string)
* `Breadcrumbs::renderIfExists()` (returns string)
* `Breadcrumbs::renderIfExists($name)` (returns string)
* `Breadcrumbs::renderIfExists($name, $param1, ...)` (returns string)
* `Breadcrumbs::renderArrayIfExists($name, $params)` (returns string)
* `Breadcrumbs::setCurrentRoute($name)`
* `Breadcrumbs::setCurrentRoute($name, $param1, ...)`
* `Breadcrumbs::setCurrentRouteArray($name, $params)`
* `Breadcrumbs::clearCurrentRoute()`
* `Breadcrumbs::setView($view)`
* `Breadcrumbs::getView()` (returns string)

### Defining breadcrumbs (inside the callback)

* `$breadcrumbs->push($title)`
* `$breadcrumbs->push($title, $url)`
* `$breadcrumbs->parent($name)`
* `$breadcrumbs->parent($name, $param1, ...)`
* `$breadcrumbs->parentArray($name, $params)`

### Outputting the breadcrumbs (in the view)

* `$breadcrumbs` (array), contains:
    * `$breadcrumb->title` (string)
    * `$breadcrumb->url` (string or null)
    * `$breadcrumb->first` (boolean)
    * `$breadcrumb->last` (boolean)

## Changelog

See the [CHANGELOG](CHANGELOG.md) for a list of changes and upgrade instructions.

## Issues & Pull Requests

**Important:** Don't be afraid to go into the Laravel Breadcrumbs code and use
`var_dump()` (or `print_r()`) to see what's happening and fix your own problems!
A [pull request](CONTRIBUTING.md) or detailed bug report is much more likely to
get attention than a vague error report. Also make sure you read the documentation
carefully.

Please submit issues and pull requests using [GitHub
issues](https://github.com/davejamesmiller/laravel-breadcrumbs/issues).

Bug reports should include the following:

* The complete error message, including file & line numbers
* Steps to reproduce the problem
* Laravel Breadcrumbs version (should be the latest version)
* Laravel version
* PHP version
* The `providers` and `aliases` sections of `app/config/app.php` (Note: **not the Encryption Key section** which should be kept private) - in case there's a conflict with another package

You may also need to include copies of:

* `app/breadcrumbs.php`
* `app/config/packages/davejamesmiller/laravel-breadcrumbs/config.php` (if used)
* The view or layout that outputs the breadcrumbs
* The custom breadcrumbs template (if applicable)
* Any other relevant files

If you have any suggestions for improving the documentation - especially if
anything is unclear to you and could be explained better - please let me know.

## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file for details of how to contribute to
Laravel Breadcrumbs.

## Thanks to

This package is largely based on the
[Gretel](https://github.com/lassebunk/gretel) plugin for Ruby on Rails, which I
used for a while before discovering Laravel.

### Contributors

* Ricky Wiens ([rickywiens](https://github.com/rickywiens)) -
  [#41](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/41)
* Boris Glumpler ([shabushabu](https://github.com/shabushabu)) -
  [#28](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/28)
* Andrej Badin ([Andreyco](https://github.com/Andreyco)) -
  [#24](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/24)
* Stef Horner ([tedslittlerobot](https://github.com/tedslittlerobot)) -
  [#11](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/11)

## License

MIT License. See [LICENSE.txt](LICENSE.txt).

## Alternatives

So far I've only found one other breadcrumb package for Laravel:

* [noherczeg/breadcrumb](https://github.com/noherczeg/breadcrumb)
