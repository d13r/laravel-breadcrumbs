# Changelog for Laravel Breadcrumbs

Uses [Semantic Versioning](http://semver.org/).

### 2.2.0

* Add `Breadcrumbs::exists()`, `renderIfExists()`, `renderArrayIfExists()`
* Use the current route name & parameters by default so you don't have to
  specify them in the view (as long as you use consistent names)

### 2.1.0

* Add support for non-linked breadcrumbs to the Twitter Bootstrap templates

### 2.0.0

* Add Twitter Bootstrap v3 template
  ([#7](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/7))
* Twitter Bootstrap v3 is now the default template
* Support for passing arrays into `render()`, `generate()` and `parent()`
  ([#8](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/8)) (note: not backwards-compatible)
    * Split `Breadcrumbs::render()` into two methods: `render($name, $arg1, $arg2)` and `renderArray($name, $params)`
    * Split `Breadcrumbs::generate()` into two methods: `generate($name, $arg1, $arg2)` and `generateArray($name, $params)`
    * Split `$breadcrumbs->parent()` into two methods: `parent($name, $arg1, $arg2)` and `parentArray($name, $params)`
* Set view name in config file instead of in `breadcrumbs.php`
  ([#10](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/10),
  [#11](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/11))
* Simplify class names ([#15](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/15))
* Add unit tests

*Please see "Upgrading from 1.x to 2.x" below for details of the changes
required when upgrading from version 1.*

### 1.0.1

* Fix for PHP 5.3 compatibility

### 1.0.0

* Initial release

## Upgrading from 1.x to 2.x

There are some backwards-compatibility breaks in version 2 so you will need to
make the following changes:

* In `app/config/app.php` change `DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider` to `DaveJamesMiller\Breadcrumbs\ServiceProvider`
* In `app/config/app.php` change `DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs` to `DaveJamesMiller\Breadcrumbs\Facade`
* The default template was changed from Bootstrap 2 to Bootstrap 3. See the
  section titled *"2. Choose/create a template to render the breadcrumbs"* above
  if you need to switch it back.

The following internal changes will not affect most people but if you have any
problems please be aware of the following:

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
