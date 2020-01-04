 ![](logo.svg)  
 Laravel Breadcrumbs
================================================================================

[![Latest Stable Version](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/v/stable)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Total Downloads](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/downloads)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Monthly Downloads](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/d/monthly)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![License](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/license)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)<br>
[![Latest Unstable Version](https://poser.pugx.org/davejamesmiller/laravel-breadcrumbs/v/unstable)](https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs)
[![Build Status](https://travis-ci.com/davejamesmiller/laravel-breadcrumbs.svg?branch=master)](https://travis-ci.com/davejamesmiller/laravel-breadcrumbs)
[![Coverage Status](https://coveralls.io/repos/github/davejamesmiller/laravel-breadcrumbs/badge.svg?branch=master)](https://coveralls.io/github/davejamesmiller/laravel-breadcrumbs?branch=master)

A simple [Laravel](https://laravel.com/)-style way to create breadcrumbs.


 Table of Contents
--------------------------------------------------------------------------------

- [Compatibility Chart](#compatibility-chart)
- [Getting Started](#getting-started)
- [Defining Breadcrumbs](#defining-breadcrumbs)
- [Custom Templates](#custom-templates)
- [Outputting Breadcrumbs](#outputting-breadcrumbs)
- [Structured Data](#structured-data)
- [Route-Bound Breadcrumbs](#route-bound-breadcrumbs)
- [Advanced Usage](#advanced-usage)
- [API Reference](#api-reference)
- [FAQ](#faq)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [No Technical Support](#no-technical-support)
- [Changelog](#changelog)
- [License](#license)


 Compatibility Chart
--------------------------------------------------------------------------------

| Laravel Breadcrumbs                                                    | Laravel      | PHP  |
|------------------------------------------------------------------------|--------------|------|
| **5.3.2**                                                              | 5.6+         | 7.1+ |
| 5.3.0 – 5.3.1                                                          | 5.6 – 6.x    | 7.1+ |
| 5.2.1                                                                  | 5.6 – 5.8    | 7.1+ |
| 5.1.1 – 5.2.0                                                          | 5.6 – 5.7    | 7.1+ |
| 5.0.0 – 5.1.0                                                          | 5.6          | 7.1+ |
| [4.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/4.x) | 5.5          | 7.0+ |
| [3.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.x) | 5.0 – 5.4    | 5.4+ |
| [2.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.x) | 4.0 – 4.2    | 5.3+ |


 Getting Started
--------------------------------------------------------------------------------

***Note:** If you are using an older version, click it in the table above to see the documentation for that version.*

***Note 2:** If you think this documentation can be improved in any way, please [edit this file](https://github.com/davejamesmiller/laravel-breadcrumbs/edit/master/README.md) and make a pull request.*

### 1. Install Laravel Breadcrumbs

Run this at the command line:

```bash
composer require davejamesmiller/laravel-breadcrumbs:5.x
```

This will update `composer.json` and install the package into the `vendor/` directory.


### 2. Define your breadcrumbs

Create a file called `routes/breadcrumbs.php` that looks like this:

```php
<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > About
Breadcrumbs::for('about', function ($trail) {
    $trail->parent('home');
    $trail->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id));
});
```

See the [Defining Breadcrumbs](#defining-breadcrumbs) section for more details.


### 3. Choose a template

By default a [Bootstrap](https://getbootstrap.com/docs/4.0/components/breadcrumb/)-compatible ordered list will be rendered, so if you're using Bootstrap 4 you can skip this step.

First initialise the config file by running this command:

```bash
php artisan vendor:publish --tag=breadcrumbs-config
```

Then open `config/breadcrumbs.php` and edit this line:

```php
    'view' => 'breadcrumbs::bootstrap4',
```

The possible values are:

- `breadcrumbs::bootstrap4` – [Bootstrap 4](https://getbootstrap.com/docs/4.0/components/breadcrumb/)
- `breadcrumbs::bootstrap3` – [Bootstrap 3](https://getbootstrap.com/docs/3.4/components/#breadcrumbs)
- `breadcrumbs::bootstrap2` – [Bootstrap 2](http://getbootstrap.com/2.3.2/components.html#breadcrumbs)
- `breadcrumbs::bulma` – [Bulma](https://bulma.io/documentation/components/breadcrumb/)
- `breadcrumbs::foundation6` – [Foundation 6](https://get.foundation/sites/docs/breadcrumbs.html)
- `breadcrumbs::materialize` – [Materialize](https://materializecss.com/breadcrumbs.html)
- `breadcrumbs::uikit` – [UIkit](https://getuikit.com/docs/breadcrumb)
- `breadcrumbs::json-ld` – [JSON-LD Structured Data](https://developers.google.com/search/docs/data-types/breadcrumbs) (&lt;script&gt; tag, no visible output)
- The path to a custom view: e.g. `partials.breadcrumbs`

See the [Custom Templates](#custom-templates) section for more details.


### 4. Output the breadcrumbs

Finally, call `Breadcrumbs::render()` in the view for each page, passing it the name of the breadcrumb to use and any additional parameters – for example:

```blade
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
Breadcrumbs::for('home', function ($trail) {
     $trail->push('Home', route('home'));
});
```

As you can see, you simply call `$trail->push($title, $url)` inside the closure.

For generating the URL, you can use any of the standard Laravel URL-generation methods, including:

- `url('path/to/route')` (`URL::to()`)
- `secure_url('path/to/route')`
- `route('routename')` or `route('routename', 'param')` or `route('routename', ['param1', 'param2'])` (`URL::route()`)
- ``action('controller@action')`` (``URL::action()``)
- Or just pass a string URL (`'http://www.example.com/'`)

This example would be rendered like this:

```blade
{{ Breadcrumbs::render('home') }}
```

And results in this output:

> Home

### Parent links

This is another static page, but this has a parent link before it:

```php
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});
```

It works by calling the closure for the `home` breadcrumb defined above.

It would be rendered like this:

```blade
{{ Breadcrumbs::render('blog') }}
```

And results in this output:

> [Home](#) / Blog

Note that the default templates do not create a link for the last breadcrumb (the one for the current page), even when a URL is specified. You can override this by creating your own template – see [Custom Templates](#custom-templates) for more details.


### Dynamic titles and links

This is a dynamically generated page pulled from the database:

```php
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('blog');
    $trail->push($post->title, route('post', $post));
});
```

The `$post` object (probably an [Eloquent](https://laravel.com/docs/5.7/eloquent) model, but could be anything) would simply be passed in from the view:

```blade
{{ Breadcrumbs::render('post', $post) }}
```

It results in this output:

> [Home](#) / [Blog](#) / Post Title

**Tip:** You can pass multiple parameters if necessary.


### Nested categories

Finally, if you have nested categories or other special requirements, you can call `$trail->push()` multiple times:

```php
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');

    foreach ($category->ancestors as $ancestor) {
        $trail->push($ancestor->title, route('category', $ancestor->id));
    }

    $trail->push($category->title, route('category', $category->id));
});
```

Alternatively you could make a recursive function such as this:

```php
Breadcrumbs::for('category', function ($trail, $category) {
    if ($category->parent) {
        $trail->parent('category', $category->parent);
    } else {
        $trail->parent('blog');
    }

    $trail->push($category->title, route('category', $category->slug));
});
```

Both would be rendered like this:

```blade
{{ Breadcrumbs::render('category', $category) }}
```

And result in this:

> [Home](#) / [Blog](#) / [Grandparent Category](#) / [Parent Category](#) / Category Title


 Custom Templates
--------------------------------------------------------------------------------

### Create a view

To customise the HTML, create your own view file (e.g. `resources/views/partials/breadcrumbs.blade.php`) like this:

```blade
@if (count($breadcrumbs))

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                <li class="breadcrumb-item"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="breadcrumb-item active">{{ $breadcrumb->title }}</li>
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
    'view' => 'partials.breadcrumbs', #--> resources/views/partials/breadcrumbs.blade.php
```


### Skipping the view

Alternatively you can skip the custom view and call `Breadcrumbs::generate()` to get the breadcrumbs [Collection](https://laravel.com/docs/5.5/collections) directly:

```blade
@foreach (Breadcrumbs::generate('post', $post) as $breadcrumb)
    {{-- ... --}}
@endforeach
```


 Outputting Breadcrumbs
--------------------------------------------------------------------------------

Call `Breadcrumbs::render()` in the view for each page, passing it the name of the breadcrumb to use and any additional parameters.


### With Blade

In the page (e.g. `resources/views/home.blade.php`):

```blade
{{ Breadcrumbs::render('home') }}
```

Or with a parameter:

```blade
{{ Breadcrumbs::render('category', $category) }}
```


### With Blade layouts and @section

In the page (e.g. `resources/views/home.blade.php`):

```blade
@extends('layout.name')

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection
```

Or using the shorthand syntax:

```blade
@extends('layout.name')

@section('breadcrumbs', Breadcrumbs::render('home'))
```

And in the layout (e.g. `resources/views/layout/name.blade.php`):

```blade
@yield('breadcrumbs')
```


### Pure PHP (without Blade)

In the page (e.g. `resources/views/home.php`):

```blade
<?= Breadcrumbs::render('home') ?>
```

Or use the longhand syntax if you prefer:

```blade
<?php echo Breadcrumbs::render('home') ?>
```


 Structured Data
--------------------------------------------------------------------------------

To render breadcrumbs as JSON-LD [structured data](https://developers.google.com/search/docs/data-types/breadcrumbs) (usually for SEO reasons), use `Breadcrumbs::view()` to render the `breadcrumbs::json-ld` template in addition to the normal one. For example:

```blade
<html>
    <head>
        ...
        {{ Breadcrumbs::view('breadcrumbs::json-ld', 'category', $category) }}
        ...
    </head>
    <body>
        ...
        {{ Breadcrumbs::render('category', $category) }}
        ...
    </body>
</html>
```

(Note: If you use [Laravel Page Speed](https://github.com/renatomarinho/laravel-page-speed) you may need to [disable the `TrimUrls` middleware](https://github.com/renatomarinho/laravel-page-speed/issues/66).)

To specify an image, add it to the `$data` parameter in `push()`:

```php
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('home');
    $trail->push($post->title, route('post', $post), ['image' => asset($post->image)]);
});
```

(If you prefer to use Microdata or RDFa you will need to create a [custom template](#custom-templates).)


 Route-Bound Breadcrumbs
--------------------------------------------------------------------------------

In normal usage you must call `Breadcrumbs::render($name, $params...)` to render the breadcrumbs on every page. If you prefer, you can name your breadcrumbs the same as your routes and avoid this duplication...


### Name your routes

Make sure each of your routes has a name. For example (`routes/web.php`):

```php
// Home
Route::name('home')->get('/', 'HomeController@index');

// Home > [Post]
Route::name('post')->get('/post/{id}', 'PostController@show');
```

For more details see [Named Routes](https://laravel.com/docs/5.5/routing#named-routes) in the Laravel documentation.


### Name your breadcrumbs to match

For each route, create a breadcrumb with the same name and parameters. For example (`routes/breadcrumbs.php`):

```php
// Home
Breadcrumbs::for('home', function ($trail) {
     $trail->push('Home', route('home'));
});

// Home > [Post]
Breadcrumbs::for('post', function ($trail, $id) {
    $post = Post::findOrFail($id);
    $trail->parent('home');
    $trail->push($post->title, route('post', $post));
});
```

To add breadcrumbs to a [custom 404 Not Found page](https://laravel.com/docs/5.5/errors#custom-http-error-pages), use the name `errors.404`:

```php
// Error 404
Breadcrumbs::for('errors.404', function ($trail) {
    $trail->parent('home');
    $trail->push('Page Not Found');
});
```


### Output breadcrumbs in your layout

Call `Breadcrumbs::render()` with no parameters in your layout file (e.g. `resources/views/app.blade.php`):

```blade
{{ Breadcrumbs::render() }}
```

This will automatically output breadcrumbs corresponding to the current route. The same applies to `Breadcrumbs::generate()`:

```php
$breadcrumbs = Breadcrumbs::generate();
```

And to `Breadcrumbs::view()`:

```blade
{{ Breadcrumbs::view('breadcrumbs::json-ld') }}
```


### Route binding exceptions

It will throw an `InvalidBreadcrumbException` if the breadcrumb doesn't exist, to remind you to create one. To disable this (e.g. if you have some pages with no breadcrumbs), first initialise the config file, if you haven't already:

```bash
php artisan vendor:publish --tag=breadcrumbs-config
```

Then open `config/breadcrumbs.php` and set this value:

```php
    'missing-route-bound-breadcrumb-exception' => false,
```

Similarly, to prevent it throwing an `UnnamedRouteException` if the current route doesn't have a name, set this value:

```php
    'unnamed-route-exception' => false,
```


### Route model binding

Laravel Breadcrumbs uses the same model binding as the controller. For example:

```php
// routes/web.php
Route::name('post')->get('/post/{post}', 'PostController@show');
```

```php
// app/Http/Controllers/PostController.php
use App\Post;

class PostController extends Controller
{
    public function show(Post $post) // <-- Implicit model binding happens here
    {
        return view('post/show', ['post' => $post]);
    }
}
```

```php
// routes/breadcrumbs.php
Breadcrumbs::for('post', function ($trail, $post) { // <-- The same Post model is injected here
    $trail->parent('home');
    $trail->push($post->title, route('post', $post));
});
```

This makes your code less verbose and more efficient by only loading the post from the database once.

For more details see [Route Model Binding](https://laravel.com/docs/5.5/routing#route-model-binding) in the Laravel documentation.


### Resourceful controllers

Laravel automatically creates route names for resourceful controllers, e.g. `photo.index`, which you can use when defining your breadcrumbs. For example:

```php
// routes/web.php
Route::resource('photo', PhotoController::class);
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
Breadcrumbs::for('photo.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Photos', route('photo.index'));
});

// Photos > Upload Photo
Breadcrumbs::for('photo.create', function ($trail) {
    $trail->parent('photo.index');
    $trail->push('Upload Photo', route('photo.create'));
});

// Photos > [Photo Name]
Breadcrumbs::for('photo.show', function ($trail, $photo) {
    $trail->parent('photo.index');
    $trail->push($photo->title, route('photo.show', $photo->id));
});

// Photos > [Photo Name] > Edit Photo
Breadcrumbs::for('photo.edit', function ($trail, $photo) {
    $trail->parent('photo.show', $photo);
    $trail->push('Edit Photo', route('photo.edit', $photo->id));
});
```

For more details see [Resource Controllers](https://laravel.com/docs/5.5/controllers#resource-controllers) in the Laravel documentation.

(Related FAQ: [Why is there no Breadcrumbs::resource() method?](#why-is-there-no-breadcrumbsresource-method).)


 Advanced Usage
--------------------------------------------------------------------------------

### Breadcrumbs with no URL

The second parameter to `push()` is optional, so if you want a breadcrumb with no URL you can do so:

```php
$trail->push('Sample');
```

The `$breadcrumb->url` value will be `null`.

The default Bootstrap templates provided render this with a CSS class of "active", the same as the last breadcrumb, because otherwise they default to black text not grey which doesn't look right.


### Custom data

The `push()` method accepts an optional third parameter, `$data` – an array of arbitrary data to be passed to the breadcrumb, which you can use in your custom template. For example, if you wanted each breadcrumb to have an icon, you could do:

```php
$trail->push('Home', '/', ['icon' => 'home.png']);
```

The `$data` array's entries will be merged into the breadcrumb as properties, so you would access the icon as `$breadcrumb->icon` in your template, like this:

```blade
<li><a href="{{ $breadcrumb->url }}">
    <img src="/images/icons/{{ $breadcrumb->icon }}">
    {{ $breadcrumb->title }}
</a></li>
```

Do not use the keys `title` or `url` as they will be overwritten.


### Before and after callbacks

You can register "before" and "after" callbacks to add breadcrumbs at the start/end of the trail. For example, to automatically add the current page number at the end:

```php
Breadcrumbs::after(function ($trail) {
    $page = (int) request('page', 1);
    if ($page > 1) {
        $trail->push("Page $page");
    }
});
```


### Getting the current page breadcrumb

To get the last breadcrumb for the current page, use `Breadcrumb::current()`. For example, you could use this to output the current page title:

```blade
<title>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</title>
```

To ignore a breadcrumb, add `'current' => false` to the `$data` parameter in `push()`. This can be useful to ignore pagination breadcrumbs:

```php
Breadcrumbs::after(function ($trail) {
    $page = (int) request('page', 1);
    if ($page > 1) {
        $trail->push("Page $page", null, ['current' => false]);
    }
});
```

```blade
<title>
    {{ ($breadcrumb = Breadcrumbs::current()) ? "$breadcrumb->title –" : '' }}
    {{ ($page = (int) request('page')) > 1 ? "Page $page –" : '' }}
    Demo App
</title>
```

For more advanced filtering, use `Breadcrumbs::generate()` and Laravel's [Collection class](https://laravel.com/docs/5.5/collections) methods instead:

```php
$current = Breadcrumbs::generate()->where('current', '!==', 'false)->last();
```


### Switching views at runtime

You can use `Breadcrumbs::view()` in place of `Breadcrumbs::render()` to render a template other than the [default one](#3-choose-a-template):

```blade
{{ Breadcrumbs::view('partials.breadcrumbs2', 'category', $category) }}
```

Or you can override the config setting to affect all future `render()` calls:

```php
Config::set('breadcrumbs.view', 'partials.breadcrumbs2');
```

```blade
{{ Breadcrumbs::render('category', $category) }}
```

Or you could call `Breadcrumbs::generate()` to get the breadcrumbs Collection and load the view manually:

```blade
@include('partials.breadcrumbs2', ['breadcrumbs' => Breadcrumbs::generate('category', $category)])
```


### Overriding the "current" route

If you call `Breadcrumbs::render()` or `Breadcrumbs::generate()` with no parameters, it will use the current route name and parameters by default (as returned by Laravel's `Route::current()` method).

You can override this by calling `Breadcrumbs::setCurrentRoute($name, $param1, $param2...)`.


### Checking if a breadcrumb exists

To check if a breadcrumb with a given name exists, call `Breadcrumbs::exists('name')`, which returns a boolean.


### Defining breadcrumbs in a different file

If you don't want to use `routes/breadcrumbs.php`, you can change it in the config file. First initialise the config file, if you haven't already:

```bash
php artisan vendor:publish --tag=breadcrumbs-config
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


### Defining/using breadcrumbs in another package

If you are creating your own package, simply load your breadcrumbs file from your service provider's `boot()` method:

```php
use Illuminate\Support\ServiceProvider;

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


### Dependency injection

You can use [dependency injection](https://laravel.com/docs/5.5/providers#the-boot-method) to access the `BreadcrumbsManager` instance if you prefer, instead of using the `Breadcrumbs::` facade:

```php
use DaveJamesMiller\Breadcrumbs\BreadcrumbsManager;
use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot(BreadcrumbsManager $breadcrumbs)
    {
        $breadcrumbs->register(...);
    }
}
```


### Macros

The BreadcrumbsManager class is [macroable](https://unnikked.ga/understanding-the-laravel-macroable-trait-dab051f09172), so you can add your own methods. For example:

```php
Breadcrumbs::macro('pageTitle', function () {
    $title = ($breadcrumb = Breadcrumbs::current()) ? "{$breadcrumb->title} – " : '';

    if (($page = (int) request('page')) > 1) {
        $title .= "Page $page – ";
    }

    return $title . 'Demo App';
});
```

```blade
<title>{{ Breadcrumbs::pageTitle() }}</title>
```


### Advanced customisations

For more advanced customisations you can subclass BreadcrumbsManager and/or BreadcrumbsGenerator, then update the config file with the new class name:

```php
    // Manager
    'manager-class' => DaveJamesMiller\Breadcrumbs\BreadcrumbsManager::class,

    // Generator
    'generator-class' => DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator::class,
```

(**Note:** Anything that's not part of the public API (see below) may change between releases, so I suggest you write unit tests to ensure it doesn't break when upgrading.)


 API Reference
--------------------------------------------------------------------------------

### `Breadcrumbs` Facade

| Method                                                              | Returns    | Added in |
|---------------------------------------------------------------------|------------|----------|
| `Breadcrumbs::for(string $name, closure $callback)`                 | void       | 5.1.0    |
| `Breadcrumbs::register(string $name, closure $callback)`            | void       | 1.0.0    |
| `Breadcrumbs::before(closure $callback)`                            | void       | 4.0.0    |
| `Breadcrumbs::after(closure $callback)`                             | void       | 4.0.0    |
| `Breadcrumbs::exists()`                                             | boolean    | 2.2.0    |
| `Breadcrumbs::exists(string $name)`                                 | boolean    | 2.2.0    |
| `Breadcrumbs::generate()`                                           | Collection | 2.2.3    |
| `Breadcrumbs::generate(string $name)`                               | Collection | 1.0.0    |
| `Breadcrumbs::generate(string $name, mixed $param1, ...)`           | Collection | 1.0.0    |
| `Breadcrumbs::render()`                                             | string     | 2.2.0    |
| `Breadcrumbs::render(string $name)`                                 | string     | 1.0.0    |
| `Breadcrumbs::render(string $name, mixed $param1, ...)`             | string     | 1.0.0    |
| `Breadcrumbs::view(string $view)`                                   | string     | 4.0.0    |
| `Breadcrumbs::view(string $view, string $name)`                     | string     | 4.0.0    |
| `Breadcrumbs::view(string $view, string $name, mixed $param1, ...)` | string     | 4.0.0    |
| `Breadcrumbs::setCurrentRoute(string $name)`                        | void       | 2.2.0    |
| `Breadcrumbs::setCurrentRoute(string $name, mixed $param1, ...)`    | void       | 2.2.0    |
| `Breadcrumbs::clearCurrentRoute()`                                  | void       | 2.2.0    |

[Source](classes/BreadcrumbsManager.php)


### Defining breadcrumbs

```php
use App\Models\Post;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::before(function (BreadcrumbsGenerator $trail) {
    // ...
});

Breadcrumbs::for('name', function (BreadcrumbsGenerator $trail, Post $post) {
    // ...
});

Breadcrumbs::after(function (BreadcrumbsGenerator $trail) {
    // ...
});
```


| Method                                                  | Returns | Added in |
|---------------------------------------------------------|---------|----------|
| `$trail->push(string $title)`                           | void    | 1.0.0    |
| `$trail->push(string $title, string $url)`              | void    | 1.0.0    |
| `$trail->push(string $title, string $url, array $data)` | void    | 2.3.0    |
| `$trail->parent(string $name)`                          | void    | 1.0.0    |
| `$trail->parent(string $name, mixed $param1, ...)`      | void    | 1.0.0    |

[Source](classes/BreadcrumbsGenerator.php)


### In the view (template)

```blade
@foreach ($breadcrumbs as $breadcrumb)
    {{-- ... --}}
@endforeach
```

| Variable                             | Type          | Added in |
|--------------------------------------|---------------|----------|
| `$breadcrumb->title`                 | string        | 1.0.0    |
| `$breadcrumb->url`                   | string / null | 1.0.0    |
| `$breadcrumb->custom_attribute_name` | mixed         | 2.3.0    |

[Source](classes/BreadcrumbsGenerator.php#L96)


### Configuration file

`config/breadcrumbs.php`

| Setting                                    | Type           | Added in |
|--------------------------------------------|----------------|----------|
| `view`                                     | string         | 2.0.0    |
| `files`                                    | string / array | 4.0.0    |
| `unnamed-route-exception`                  | boolean        | 4.0.0    |
| `missing-route-bound-breadcrumb-exception` | boolean        | 4.0.0    |
| `invalid-named-breadcrumb-exception`       | boolean        | 4.0.0    |
| `manager-class`                            | string         | 4.2.0    |
| `generator-class`                          | string         | 4.2.0    |

[Source](config/breadcrumbs.php)


 FAQ
--------------------------------------------------------------------------------

### There's a new version of Laravel - can you add support for it?

Since version 5.3.2, there is no maximum version of Laravel specified in [composer.json](composer.json), so most of the time it will just work.

If it breaks for any reason, it will be fixed when (1) someone [submits a pull request](#new-version-of-laravel) to fix it, or (2) I decide to upgrade my own applications - whichever comes first. In practice it's usually the former because I don't generally upgrade on day 1.


### Why is there no Breadcrumbs::resource() method?

A few people have suggested adding `Breadcrumbs::resource()` to match [`Route::resource()`](https://laravel.com/docs/5.7/controllers#resource-controllers), but no-one has come up with a good implementation that (a) is flexible enough to deal with translations, nested resources, etc., and (b) isn't overly complex as a result.

Personally I don't think there is a good all-round solution, so instead I recommend adding your own using `Breadcrumbs::macro()`. Here's a starting point:

```php
Breadcrumbs::macro('resource', function ($name, $title) {
    // Home > Blog
    Breadcrumbs::for("$name.index", function ($trail) use ($name, $title) {
        $trail->parent('home');
        $trail->push($title, route("$name.index"));
    });

    // Home > Blog > New
    Breadcrumbs::for("$name.create", function ($trail) use ($name) {
        $trail->parent("$name.index");
        $trail->push('New', route("$name.create"));
    });

    // Home > Blog > Post 123
    Breadcrumbs::for("$name.show", function ($trail, $model) use ($name) {
        $trail->parent("$name.index");
        $trail->push($model->title, route("$name.show", $model));
    });

    // Home > Blog > Post 123 > Edit
    Breadcrumbs::for("$name.edit", function ($trail, $model) use ($name) {
        $trail->parent("$name.show", $model);
        $trail->push('Edit', route("$name.edit", $model));
    });
});

Breadcrumbs::resource('blog', 'Blog');
Breadcrumbs::resource('photos', 'Photos');
Breadcrumbs::resource('users', 'Users');
```

Note that this *doesn't* deal with translations or nested resources, and it assumes that all models have a `title` attribute (which users probably don't). Adapt it however you see fit.


 Troubleshooting
--------------------------------------------------------------------------------

#### General

- Re-read the instructions and make sure you did everything correctly.
- Start with the simple options and only use the advanced options (e.g. Route-Bound Breadcrumbs) once you understand how it works.

#### Class 'Breadcrumbs' not found

- Try running `composer update davejamesmiller/laravel-breadcrumbs` to upgrade.
- Try running `php artisan package:discover` to ensure the service provider is detected by Laravel.

#### Breadcrumb not found with name ...

- Make sure you register the breadcrumbs in the right place (`routes/breadcrumbs.php` by default).
    - Try putting `dd(__FILE__)` in the file to make sure it's loaded.
    - Try putting `dd($files)` in `BreadcrumbsServiceProvider::registerBreadcrumbs()` to check the path is correct.
    - If not, try running `php artisan config:clear` (or manually delete `bootstrap/cache/config.php`) or update the path in `config/breadcrumbs.php`.
- Make sure the breadcrumb name is correct.
    - If using Route-Bound Breadcrumbs, make sure it matches the route name exactly.
- To suppress these errors when using Route-Bound Breadcrumbs (if you don't want breadcrumbs on some pages), set [`missing-route-bound-breadcrumb-exception` to `false`](#route-binding-exceptions) in the config file.

#### BreadcrumbsServiceProvider::registerBreadcrumbs(): Failed opening required ...

- Make sure the path is correct.
- If so, check the file ownership & permissions are correct.
- If not, try running `php artisan config:clear` (or manually delete `bootstrap/cache/config.php`) or update the path in `config/breadcrumbs.php`.

#### Undefined variable: breadcrumbs

- Make sure you use `{{ Breadcrumbs::render() }}` or `{{ Breadcrumbs::view() }}`, not `@include()`.

#### Method for does not exist

- You're probably using a version [older than 5.1](#v510-sat-5-may-2018) - use `Breadcrumbs::register()` instead of `Breadcrumbs::for()` (or upgrade).

#### Something else

Sorry I wasn't able to help this time, but once you have solved your problem, please [edit this file](https://github.com/davejamesmiller/laravel-breadcrumbs/edit/master/README.md) with the solution to help the next person!


 Contributing
--------------------------------------------------------------------------------

**Documentation:** If you think the documentation can be improved in any way, please do [edit this file](https://github.com/davejamesmiller/laravel-breadcrumbs/edit/master/README.md) and make a pull request.

**Bug fixes:** Please fix it and open a [pull request](https://github.com/davejamesmiller/laravel-breadcrumbs/pulls). ([See below](#creating-a-pull-request) for more detailed instructions.) Bonus points if you add a unit test to make sure it doesn't happen again!

**New features:** Only features with a clear use case and well-considered API will be accepted. They must be documented and include unit tests. If in doubt, make a proof-of-concept (either code or documentation) and open a [pull request](https://github.com/davejamesmiller/laravel-breadcrumbs/pulls) to discuss the details. (Tip: If you want a feature that's too specific to be included by default, see [Macros](#macros) or [Advanced customisations](#advanced-customisations) for ways to add them.)


### Creating a pull request

The easiest way to work on Laravel Breadcrumbs is to tell Composer to install it from source (Git) using the `--prefer-source` flag:

```bash
rm -rf vendor/davejamesmiller/laravel-breadcrumbs
composer install --prefer-source
```

Then checkout the master branch and create your own local branch to work on:

```bash
cd vendor/davejamesmiller/laravel-breadcrumbs
git checkout -t origin/master
git checkout -b YOUR_BRANCH
```

Now make your changes, including unit tests and documentation (if appropriate). Run the unit tests to make sure everything is still working:

```bash
scripts/test.sh
```

Then commit the changes. [Fork the repository on GitHub](https://github.com/davejamesmiller/laravel-breadcrumbs/fork) if you haven't already, and push your changes to it:

```bash
git remote add YOUR_USERNAME git@github.com:YOUR_USERNAME/laravel-breadcrumbs.git
git push -u YOUR_USERNAME YOUR_BRANCH
```

Finally, browse to the repository on GitHub and create a pull request.

(Alternatively, there is a [test app](https://github.com/davejamesmiller/laravel-packages-test) that you can use.)


### Using your fork in a project

To use your own fork in a project, update the `composer.json` in your main project as follows:

```json5
{
    // ADD THIS:
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/YOUR_USERNAME/laravel-breadcrumbs.git"
        }
    ],
    "require": {
        // UPDATE THIS:
        "davejamesmiller/laravel-breadcrumbs": "dev-YOUR_BRANCH"
    }
}
```

Replace `YOUR_USERNAME` with your GitHub username and `YOUR_BRANCH` with the branch name (e.g. `develop`). This tells Composer to use your repository instead of the default one.


### Unit tests

To run the unit tests:

```bash
scripts/test.sh
```

To check code coverage:

```bash
scripts/test-coverage.sh
```

Then open `test-coverage/index.html` to view the results. Be aware of the [edge cases](https://phpunit.de/manual/current/en/code-coverage-analysis.html#code-coverage-analysis.edge-cases) in PHPUnit that can make it not-quite-accurate.


### New version of Laravel

There is no maximum version specified in [`composer.json`](composer.json), so there is no need for a new version of Laravel Breadcrumbs to be released every 6 months. However, this file will need to be updated to run tests against the new version:

- [`.travis.yml`](.travis.yml)
    - `matrix` (Laravel versions)
    - `php` (PHP versions)
    - `exclude` (Unsupported combinations)

If changes are required, also update:

- [`README.md`](README.md)
    - [Compatibility Chart](README.md#compatibility-chart)
    - [Changelog](README.md#changelog)

If backwards-incompatible changes cause the **minimum** supported versions of Laravel or PHP to change, update:

- [`composer.json`](composer.json)
    - `php/*`
    - `illuminate/*`

### Releasing a new version

*This section is for maintainers only.*

- Ensure the unit tests are updated and have 100% coverage
- Update the [test app](https://github.com/davejamesmiller/laravel-packages-test), if appropriate, and test it manually
- Ensure the [README](README.md) is up to date, including:
    - Document any new features
    - [Compatibility Chart](README.md#compatibility-chart)
    - [Changelog](README.md#changelog)
- Merge the changes into the `master` branch (if necessary)
- Push the code changes to GitHub (`git push`)
- Make sure [all tests are passing](https://travis-ci.org/davejamesmiller/laravel-breadcrumbs)
- Tag the release (`git tag 1.2.3`)
- Push the tag (`git push --tag`)


 No Technical Support
--------------------------------------------------------------------------------

Sorry, I don't offer any technical support, and GitHub Issues are disabled. That means I won't figure out why it's not working for you, I won't fix bugs for you, and I won't write new features on request - this is **free** software after all.

**But** the beauty of open source is you can do whatever you want with it! You can fork it, fix it, improve it and extend it. If you don't want to maintain your own fork, and you think other people would benefit from your changes, you can submit a [pull request](https://github.com/davejamesmiller/laravel-breadcrumbs/pulls) to have your changes included in the next release.

If you get really stuck, I suggest you:

1. Read and re-read both this file and the [Laravel documentation](https://laravel.com/docs) to see if you missed something.
2. Dive into the source code and spend some time figuring out how it's meant to work and what's actually happening.
3. Try to reproduce the problem on a brand new Laravel project, in case it's an incompatibility with another package or your other code.
4. Ask your colleagues to help you debug it, if you work in a team.
5. Pay someone more experienced to help you (or if you work for a company, ask your boss to pay them).
6. Try posting on [Stack Overflow](https://stackoverflow.com/search?q=laravel+breadcrumbs), [Laravel.io Forum](https://laravel.io/forum) or [Laracasts Forum](https://laracasts.com/discuss) (but I can't promise anyone will answer - they don't get paid either).
7. Use a different package instead.
8. Write your own.


 Changelog
--------------------------------------------------------------------------------

*Laravel Breadcrumbs uses [Semantic Versioning](http://semver.org/).*


### [v5.3.2](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.3.2) (Mon 30 Dec 2019)

- Remove the maximum Laravel version constraint from composer.json, to support Laravel 7+ without requiring a new release every 6 months


### [v5.3.1](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.3.1) (Sun 20 Oct 2019)

- Add a docblock to the `Breadcrumbs` facade (alternative to [IDE Helper](https://github.com/barryvdh/laravel-ide-helper) – thanks to [Alexandr Chernyaev](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/206))


### [v5.3.0](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.3.0) (Tue 3 Sep 2019)

- Add Laravel 6.x support
- Add [Laravel Ignition](https://freek.dev/1441-ignition-a-new-error-page-for-laravel) suggested solutions
- Change `vendor:publish` tag from `config` to `breadcrumbs-config` to match Horizon & Telescope and simplify the command


### [v5.2.1](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.2.1) (Wed 27 Feb 2019)

- Add Laravel 5.8 support (thanks to [Andrew Dabich](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/203))


### [v5.2.0](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.2.0) (Tue 30 Oct 2018)

- Add [UIkit](https://getuikit.com/docs/breadcrumb) template (`breadcrumbs::uikit`)
  ([#198](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/198) by [PieterHollevoet](https://github.com/PieterHollevoet))


### [v5.1.2](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.1.2) (Fri 14 Sep 2018)

- Update [default config file](config/breadcrumbs.php) with the full list of available views


### [v5.1.1](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.1.1) (Wed 5 Sep 2018)

- Add Laravel 5.7 support


### [v5.1.0](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.1.0) (Sat 5 May 2018)

- Add `Breadcrumbs::for($name, $callback)` as an alias for `Breadcrumbs::register($name, $callback)`
- Renamed `$breadcrumbs` to `$trail` in documentation (this doesn't affect the code)

These changes were inspired by (read: taken directly from) [Dwight Watson's Breadcrumbs package](https://github.com/dwightwatson/breadcrumbs).

#### Upgrading from 5.0.0 to 5.1.0

No changes are required, but I recommend updating your `routes/breadcrumbs.php` to match the new documentation:

- Replace `Breadcrumbs::register` with `Breadcrumbs::for`
- Replace `$breadcrumbs` with `$trail`


### [v5.0.0](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/5.0.0) (Sat 10 Feb 2018)

- Add Laravel 5.6 support, and drop support for Laravel 5.5
- Drop PHP 7.0 support (add `void` return type hint, and use `[]` instead of `list()`)
- Fix class names in PhpDoc for `Breadcrumbs` facade when using [IDE Helper](https://github.com/barryvdh/laravel-ide-helper)

#### Upgrading from 4.x to 5.x

- [Upgrade to Laravel 5.6](https://laravel.com/docs/5.6/upgrade) (requires PHP 7.1.3+)
- If you are extending any classes, add `: void` return type hints where needed.


### Older versions

- [Changelog for v4.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/4.x#changelog)
- [Changelog for v3.x](https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.x#changelog)
- [Changelog for v2.x and below](https://github.com/davejamesmiller/laravel-breadcrumbs/blob/2.x/CHANGELOG.md)


 License
--------------------------------------------------------------------------------

*[MIT License](https://choosealicense.com/licenses/mit/)*

**Copyright © 2013-2019 Dave James Miller**

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
