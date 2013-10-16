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
composer require davejamesmiller/laravel-breadcrumbs ~2.1.0
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
`app/views/_partials/breadcrumbs.blade.php`) and alter the config to point to
that file (e.g. `_partials.breadcrumbs`).

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

### 3. Output the breadcrumbs in your view

Finally, call `Breadcrumbs::render()` in the view template for each page,
passing in the name of the page and any parameters you defined above.

#### With Blade

```html+php
{{ Breadcrumbs::render('home') }}
```

Or with parameters:

```html+php
{{ Breadcrumbs::render('category', $category) }}
```

#### With Blade and @section

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

## Upgrading from 1.x to 2.x

There are some backwards-compatibility breaks in version 2:

* In `app/config/app.php` change `DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider` to `DaveJamesMiller\Breadcrumbs\ServiceProvider`
* In `app/config/app.php` change `DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs` to `DaveJamesMiller\Breadcrumbs\Facade`
* The default template was changed from Bootstrap 2 to Bootstrap 3. See the
  section titled *"2. Choose/create a template to render the breadcrumbs"* above
  if you need to switch it back.
* The view namespace was changed from `breadcrumbs` to `laravel-breadcrumbs` to
  match the Composer project name.
* The Bootstrap 2 template name was changed from `breadcrumbs::bootstrap` to
  `laravel-breadcrumbs::bootstrap2`.
* If you pass arrays into any of the methods, please read the following section:

### Passing arrays into `render()`, `generate()` and `parent()`

In **version 1.x** you could pass an array into each of these methods and it was
split up into several parameters. For example:

```php
// If this breadcrumb is defined:
Breadcrumbs::register('page', function($breadcrumbs, $param1, $param2)
{
    $breadcrumbs->push($param1, $param2);
});

// Then this:
Breadcrumbs::render('page', array('param1', 'param2'));

// Was equivalent to this:
Breadcrumbs::render('page', 'param1', 'param2');

// To pass an array as the first parameter you would have to do this instead:
Breadcrumbs::render('page', array(array('param1A', 'param1B')));
```

This means you couldn't pass an array as the first parameter unless you wrapped
all parameters in another array
([issue #8](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/8)).

In **version 2.x** this has been split into two methods:

```php
Breadcrumbs::register('page', function($breadcrumbs, $param1, $param2)
{
    $breadcrumbs->push($param1, $param2);
});

// Now this:
Breadcrumbs::renderArray('page', array('param1', 'param2'));

// Is equivalent to this:
Breadcrumbs::render('page', 'param1', 'param2');

// And this only passes a single parameter (an array) to the callback:
Breadcrumbs::render('page', array('param1A', 'param1B'));
```

Similarly `Breadcrumbs::generateArray()` and `$breadcrumbs->parentArray()`
methods are available, which take a single array argument. These are primarily
for internal use - most likely you won't need to call them.

## Changelog
### 2.1.0
* Add support for non-linked breadcrumbs to the Twitter Bootstrap templates

### 2.0.0
* Add Twitter Bootstrap v3 template
  ([#7](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/7))
* Twitter Bootstrap v3 is now the default template
* Support for passing arrays into `render()`, `generate()` and `parent()`
  ([#8](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/8)) (note: not backwards-compatible)
    * Split `Breadcrumbs::render()` into two methods: `render($name, $arg1, $arg2)` and `renderArray($name, $args)`
    * Split `Breadcrumbs::generate()` into two methods: `generate($name, $arg1, $arg2)` and `generateArray($name, $args)`
    * Split `$breadcrumbs->parent()` into two methods: `parent($name, $arg1, $arg2)` and `parentArray($name, $args)`
* Set view name in config file instead of in `breadcrumbs.php`
  ([#10](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/10),
  [#11](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/11))
* Simplify class names ([#15](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/15))
* Add unit tests

### 1.0.1
* Fix for PHP 5.3 compatibility

### 1.0.0
* Initial release

## Information for developers

### Unit tests
To run the unit tests, simply [install PHP Unit](http://phpunit.de/manual/current/en/installation.html)
and run:

```bash
cd /path/to/laravel-breadcrumbs
phpunit
```

### Code coverage in unit tests
To check code coverage, you will also need [Xdebug](http://xdebug.org/)
installed. Run:

```bash
cd /path/to/laravel-breadcrumbs
php -d xdebug.coverage_enable=On `which phpunit` --coverage-html test-coverage
```

Then open `test-coverage/index.html` to view the results. However, be aware of
the [edge cases](http://phpunit.de/manual/current/en/code-coverage-analysis.html#code-coverage-analysis.edge-cases)
in PHPUnit.

### Developing against a real application

To develop with a real Laravel application, clone the repository into
`workbench/davejamesmiller/laravel-breadcrumbs/` then run
`composer install --dev` and it will be used instead of the one in `vendor/`.

```bash
cd /path/to/repo
mkdir -p workbench/davejamesmiller
git clone https://github.com/davejamesmiller/laravel-breadcrumbs.git workbench/davejamesmiller/laravel-breadcrumbs
cd workbench/davejamesmiller/laravel-breadcrumbs
composer install --dev
```

Be aware that some things don't work the same in workbench - e.g.
`php artisan config:publish davejamesmiller/laravel-breadcrumbs` will always use
the files in `vendor/` not `workbench/` unless you add the `--path` option.

### Releasing a new version
* Make sure all tests pass and also check the code coverage report
* Check the README is up to date
* Commit all changes
* Push the code changes (`git push`)
* Double-check the [Travis CI results](https://travis-ci.org/davejamesmiller/laravel-breadcrumbs)
* Tag the release (`git tag 1.2.3`)
* Push the tag (`git push --tag`)

## Thanks to
This package is largely based on the
[Gretel](https://github.com/lassebunk/gretel) plugin for Ruby on Rails, which I
used for a while before Laravel lured me back to PHP.

### Contributors
* Stef Horner ([tedslittlerobot](https://github.com/tedslittlerobot))

## License
MIT License. See [LICENSE.txt](LICENSE.txt).

## Alternatives
So far I've only found one other breadcrumb package for Laravel:

* [noherczeg/breadcrumb](https://github.com/noherczeg/breadcrumb)
