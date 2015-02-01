################################################################################
 Basic Usage
################################################################################

.. only:: html

    .. contents::
        :local:


================================================================================
 1. Define your breadcrumbs
================================================================================

Create a file called ``app/Http/breadcrumbs.php`` that looks like this:

.. code-block:: php

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

See the :doc:`defining` section for an explanation.


.. _choose-template:

================================================================================
 2. Choose a template
================================================================================

By default a `Twitter Bootstrap v3 <http://getbootstrap.com/components/#breadcrumbs>`_-compatible unordered list will be rendered.

If you would like to change the template, first you need to generate a config file by running this command:

.. code-block:: bash

    $ php artisan vendor:publish

Then open ``config/breadcrumbs.php`` and edit this line:

.. code-block:: php

    'view' => 'breadcrumbs::bootstrap3',

The possible values are:

- ``breadcrumbs::bootstrap3`` (Twitter Bootstrap 3)
- ``breadcrumbs::bootstrap2`` (Twitter Bootstrap 2)
- A path to a custom template, e.g. ``_partials.breadcrumbs``


----------------------------------------
 Creating a custom template
----------------------------------------

If you want to customise the HTML, create your own view file (e.g. ``resources/views/_partials/breadcrumbs.blade.php``) like this:

.. code-block:: html+php

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

As you can see above it will receive an array called ``$breadcrumbs``. Each breadcrumb is an object with the following keys:

- ``title`` - The title you set above
- ``url`` - The URL you set above
- ``first`` - ``true`` for the first breadcrumb (top level), ``false`` otherwise
- ``last`` - ``true`` for the last breadcrumb (current page), ``false`` otherwise

Then update your config file with the custom view name, e.g.:

.. code-block:: php

    'view' => '_partials.breadcrumbs',


================================================================================
 3. Output the breadcrumbs
================================================================================

----------------------------------------
 With Blade
----------------------------------------

Finally, call ``Breadcrumbs::render()`` in the view template for each page. You can either pass the name of the breadcrumb to use (and parameters if needed):

.. code-block:: html+php

    {!! Breadcrumbs::render('home') !!}
    {!! Breadcrumbs::render('category', $category) !!}

Or you can avoid the need to do this for every page by naming your breadcrumbs the same as your routes. For example, if you have this in ``routes.php``:

.. code-block:: php

    Route::model('category', 'Category');
    Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);
    Route::get('/category/{category}', ['uses' => 'CategoryController@show', 'as' => 'category']);

And in the layout you have this:

.. code-block:: html+php

    {!! Breadcrumbs::render() !!}

Then on the homepage it will be the same as calling ``Breadcrumbs::render('home')`` and on the category page it will be the same as calling ``Breadcrumbs::render('category', $category)``.

The key here is the ``'as'`` parameter must match the breadcrumb name. The parameters passed to the breadcrumbs callback will be the same as the ones Laravel passes to the controller (see the `Route parameters <http://laravel.com/docs/routing#route-parameters>` section of the Laravel documentation).


----------------------------------------
 With Blade layouts and @section
----------------------------------------

In the main page:

.. code-block:: html+php

    @extends('layout.name')

    @section('breadcrumbs', Breadcrumbs::render('category', $category))

In the layout:

.. code-block:: html+php

    @yield('breadcrumbs')


----------------------------------------
 Pure PHP, without Blade
----------------------------------------

.. code-block:: html+php

    <?= Breadcrumbs::render('category', $category) ?>

Or the long syntax if you prefer:

.. code-block:: html+php

    <?php echo Breadcrumbs::render('category', $category) ?>
