# Laravel Breadcrumbs

A simple Laravel-style way to create breadcrumbs in
[Laravel 4](http://four.laravel.com/).

## Installation

### 1. Install with Composer
```bash
composer require davejamesmiller/laravel-breadcrumbs dev-master
```

This will update `composer.json` and install it into the `vendor/` directory.

**Note:** `dev-master` is the latest development version. See the
[Packagist website](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
for a list of stable versions.

### 2. Add to `app/config/app.php`
```php
    'providers' => array(
        // ...
        'DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider',
    ),
```

And:

```php
    'aliases' => array(
        // ...
        'Breadcrumbs'     => 'DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs',
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
//Breadcrumbs::setView('_partials/breadcrumbs');

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

### 2. Choose/create a template to renders the breadcrumbs

You have two options to set the view used by the render method:

- Config option:

You can override the config option `breadcrumbs::view` with the dot notation to whatever view you like.

(run `php artisan config:publish DaveJamesMiller/laravel-breadcrumbs`)

- Runtime option:

You can set the view at runtime using

```php
Breadcrumbs::setView('path.to.view');
```

There are two presets already included in this package:

#### Twitter Bootstrap 2

By default, a
[Twitter Bootstrap v2](http://getbootstrap.com/2.3.2/components.html#breadcrumbs)-compatible
unordered list will be rendered.


#### Twitter Bootstrap 3

A [Twitter Bootstrap v3](http://getbootstrap.com/components/#breadcrumbs)-compatible
list can be rendered by using the key `laravel-breadcrumbs::bootstrap3`.

#### Custom template

If you want to customise the HTML, set the view to the dot notation path to your view (eg. `_partials.breadcrumbs`), and create your own view file (e.g.
    `app/views/_partials/breadcrumbs.blade.php`) like this:

```html+php
@if ($breadcrumbs)
    <ul class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$breadcrumb->last)
                <li>
                    <a href="{{{ $breadcrumb->url }}}">{{{ $breadcrumb->title }}}</a>
                    <span class="divider">/</span>
                </li>
            @else
                <li class="active">
                    {{{ $breadcrumb->title }}}
                </li>
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

For example, with Blade:

```html+php
{{ Breadcrumbs::render('home') }}
or
{{ Breadcrumbs::render('category', $category) }}
```

Or you can assign them to a section to be used in the layout:

```html+php
@section('breadcrumbs', Breadcrumbs::render('category', $category))
```

(Then in the layout you would call `@yield('breadcrumbs')`.)

Or if you aren't using Blade you can use regular PHP instead:

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

### Defining breadcrumbs in a different file

If you don't want to use `app/breadcrumbs.php`, you can define them in
`routes.php`, `start/global.php`, or any other file as long as it's loaded by
Laravel.

### Switching views dynamically

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

### Passing arrays into `render()`, `generate()` and `parent()`

In **version 1.x** you can pass an array into each of these methods and it is
split up into several parameters:

```php
Breadcrumbs::register('page', function($breadcrumbs, $param1, $param2)
{
    $breadcrumbs->parent('somethingElse', array('paramA', 'paramB'))
    $breadcrumbs->push($param1, $param2);
});

// Then this:
Breadcrumbs::render('page', array('param1', 'param2'));
Breadcrumbs::generate('page', array('param1', 'param2'));

// Is equivalent to this:
Breadcrumbs::render('page', 'param1', 'param2');
Breadcrumbs::generate('page', 'param1', 'param2');

// If you want to pass an array as the first parameter you have to do this:
Breadcrumbs::render('page', array(array('param1A', 'param1B'), 'param2'));
Breadcrumbs::generate('page', array(array('param1A', 'param1B'), 'param2'));
```

This means you can't pass an array as the first parameter unless you wrap all
parameters in another array
([issue #8](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/8)).

In **version 2.x** (currently in development) this is split into two methods:

```php
Breadcrumbs::register('page', function($breadcrumbs, $param1, $param2)
{
    $breadcrumbs->parent('somethingElse', array('paramA', 'paramB'))
    $breadcrumbs->push($param1, $param2);
});

// Now this:
Breadcrumbs::renderArray('page', array('param1', 'param2'));
Breadcrumbs::generateArray('page', array('param1', 'param2'));

// Is equivalent to this:
Breadcrumbs::render('page', 'param1', 'param2');
Breadcrumbs::generate('page', 'param1', 'param2');

// And this only passes a single parameter (an array) to the callback:
Breadcrumbs::render('page', array('param1A', 'param1B'));
Breadcrumbs::generate('page', array('param1A', 'param1B'));
```

Similarly `generateArray()` and `parentArray()` methods are available.

## Changelog
### Work in progress
* Add `Breadcrumbs::active()` method for highlighting menu items

### Development (`master` branch)
* Add Twitter Bootstrap v3 template ([#7](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/7))
* Support for passing arrays into `render()`, `generate()` and `parent()` ([#8](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/8)) (note: not backwards-compatible)
    * Split `Breadcrumbs::render()` into two methods: `render($name, $arg1, $arg2)` and `renderArray($name, $args)`
    * Split `Breadcrumbs::generate()` into two methods: `generate($name, $arg1, $arg2)` and `generateArray($name, $args)`
    * Split `$breadcrumbs->parent()` into two methods: `parent($name, $arg1, $arg2)` and `parentArray($name, $args)`

### 1.0.1
* Fix for PHP 5.3 compatibility

### 1.0.0
* Initial release

## Thanks to
This is largely based on the [Gretel](https://github.com/lassebunk/gretel) plugin for Ruby on Rails, which I used
for a while before Laravel lured me back to PHP.

## License
MIT License. See [LICENSE.txt](LICENSE.txt).

## Alternatives
So far I've only found one other breadcrumb package for Laravel:

* [noherczeg/breadcrumb](https://github.com/noherczeg/breadcrumb)
