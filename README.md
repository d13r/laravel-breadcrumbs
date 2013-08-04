# Laravel Breadcrumbs

A simple Laravel-style way to create breadcrumbs in [Laravel 4][1].

## Installation

### 1. Install with Composer
```bash
composer require davejamesmiller/laravel-breadcrumbs dev-master
```

This will update `composer.json` and install it into the `vendor/` directory.

**Note:** `dev-master` is the latest development version.
See the [Packagist website][2] for a list of other versions.

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

#### Twitter Bootstrap 2

By default, a [Twitter Bootstrap v2][3]-compatible unordered list will be
rendered.

#### Twitter Bootstrap 3

A [Twitter Bootstrap v3][4]-compatible list can be rendered by adding this line
to `app/breadcrumbs.php`:

```php
Breadcrumbs::setView('breadcrumbs::bootstrap3');
```

#### Custom template

If you want to customise the HTML, create your own view file (e.g.
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

Then add this line to `app/breadcrumbs.php`, adjusting the view name as
necessary:

```php
Breadcrumbs::setView('_partials/breadcrumbs');
```

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

## Changelog
### Work in progress
* Add `Breadcrumbs::active()` method for highlighting menu items

### Development (`master` branch)
* Add Twitter Bootstrap v3 template (#7)
* Support for passing arrays into `render()`, `generate()` and `parent()` (#8) (note: not backwards-compatible)
    * Split `Breadcrumbs::render()` into two methods: `render($name, $arg1, $arg2)` and `renderArray($name, $args)`
    * Split `Breadcrumbs::generate()` into two methods: `generate($name, $arg1, $arg2)` and `generateArray($name, $args)`
    * Split `$breadcrumbs->parent()` into two methods: `parent($name, $arg1, $arg2)` and `parentArray($name, $args)`

### 1.0.1
* Fix for PHP 5.3 compatibility

### 1.0.0
* Initial release

## Thanks to
This is largely based on the [Gretel][5] plugin for Ruby on Rails, which I used
for a while before Laravel lured me back to PHP.

## License
MIT License. See [LICENSE.txt][6].

## Alternatives
So far I've only found one other breadcrumb package for Laravel:

* [noherczeg/breadcrumb][7]

[1]: http://four.laravel.com/
[2]: https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs
[3]: http://getbootstrap.com/2.3.2/components.html#breadcrumbs
[4]: http://getbootstrap.com/components/#breadcrumbs
[5]: https://github.com/lassebunk/gretel
[6]: LICENSE.txt
[7]: https://github.com/noherczeg/breadcrumb
