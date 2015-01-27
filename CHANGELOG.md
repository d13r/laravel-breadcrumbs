# Changelog for Laravel Breadcrumbs

Uses [Semantic Versioning](http://semver.org/).

### 2.3.0

- ~~Add Laravel 5 support
  ([#49](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/49 "Doesn't work with Laravel 5.0"),
  [#50](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/50 "Added laravel 5 support"),
  [#53](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/53 "Check path for Laravel 5"),
  [#54](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/54 "Laravel 5 support"))~~ (Broken again since [3a0afc2](https://github.com/laravel/framework/commit/3a0afc20f25ad3bed640ff1a14957f972d123cf7)!)
- Add `$data` parameter to `$breadcrumb->push()` to allow for arbitrary data.
  ([#34](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/34 "How to add icon to breadcrumbs?"),
  [#35](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/35 "Add arbitrary data to breadcrumbs"),
  [#55](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/55 "Add ability to pass arbitrary data into breadcrumbs"),
  [#56](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/56 "Add ability to pass custom data into breadcrumbs"))

### 2.2.3

- Fix `Breadcrumbs::generate()` with no parameters so it uses the current route,
  as `Breadcrumbs::render()` does.
  ([#46](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/46 "Breadcrumbs::generateArray() without parameters"))

### 2.2.2

- Support for Laravel's `App::missing()` method when using automatic route detection
  ([#40](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/40 "Add support for not errorring out when a route is not set"),
  [#41](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/41 "Allow missing routes (App::missing())"))

### 2.2.1

- Laravel 4.2 support
  ([#21](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/21 "Laravel 4.2 - Argument 1 passed to __construct() must be an instance of Illuminate\View\Environment"),
  [#28](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/28 "Added support for Laravel 4.2"))

### 2.2.0

- Add `Breadcrumbs::exists()`, `renderIfExists()`, `renderArrayIfExists()`
  ([#22](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/22 "Breadcrumbs::exists()"))
- Use the current route name & parameters by default so you don't have to
  specify them in the view (as long as you use consistent names)
  ([#16](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/16 "Automatically determine breadcrumbs from current route"),
  [#24](https://github.com/davejamesmiller/laravel-breadcrumbs/pull/24 "Let Breadcrumbs auto guess breadcrumb name"))

### 2.1.0

- Add support for non-linked breadcrumbs to the Twitter Bootstrap templates
  ([#20](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/20 "[Request] Allow for breadcrumb items without links"))

### 2.0.0

- Add Twitter Bootstrap v3 template
  ([#7](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/7 "Bootstrap 3 RC1"))
- Twitter Bootstrap v3 is now the default template
- Support for passing arrays into `render()`, `generate()` and `parent()`
  ([#8](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/8 "How to use 2nd argument of the callback function (of Breadcrumb::register)?"))
  (**not backwards-compatible**)
    - Split `Breadcrumbs::render()` into two methods: `render($name, $arg1, $arg2)` and `renderArray($name, $params)`
    - Split `Breadcrumbs::generate()` into two methods: `generate($name, $arg1, $arg2)` and `generateArray($name, $params)`
    - Split `$breadcrumbs->parent()` into two methods: `parent($name, $arg1, $arg2)` and `parentArray($name, $params)`
- Set view name in config file instead of in `breadcrumbs.php`
  ([#10](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/10 "[PROPOSAL] Set the default view in a config file, rather than in application code"),
  [#11](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/11 "Allows setting of a config view through config options rather than runtime settings."))
- Simplify class names
  ([#15](https://github.com/davejamesmiller/laravel-breadcrumbs/issues/15 "Simplify class structure"))
- Add unit tests

*Please see "Upgrading from 1.x to 2.x" below for details of the changes
required when upgrading from version 1.*

### 1.0.1

- Fix for PHP 5.3 compatibility

### 1.0.0

- Initial release

## Upgrading from 1.x to 2.x

There are some backwards-compatibility breaks in version 2 so you will need to
make the following changes:

- In `app/config/app.php` change `DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider` to `DaveJamesMiller\Breadcrumbs\ServiceProvider`
- In `app/config/app.php` change `DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs` to `DaveJamesMiller\Breadcrumbs\Facade`
- The default template was changed from Bootstrap 2 to Bootstrap 3. See the README section titled "[*2. Choose/create a template to render the breadcrumbs*](README.md#2-choosecreate-a-template-to-render-the-breadcrumbs)" if you need to switch it back.

The following internal changes will not affect most people but if you have any
problems please be aware of the following:

- The view namespace was changed from `breadcrumbs` to `laravel-breadcrumbs` to
  match the Composer project name.
- The Bootstrap 2 template name was changed from `breadcrumbs::bootstrap` to
  `laravel-breadcrumbs::bootstrap2`.
- If you pass arrays into any of the methods, please read the following section:

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
