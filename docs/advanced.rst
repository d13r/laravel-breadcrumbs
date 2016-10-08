.. warning::

    Laravel Breadcrumbs is no longer maintained. Please see the `README <https://github.com/davejamesmiller/laravel-breadcrumbs/blob/master/README.rst>`_ for more details.

################################################################################
 Advanced Usage
################################################################################

.. only:: html

    .. contents::
        :local:


.. _no-url:

================================================================================
 Breadcrumbs with no URL
================================================================================

The second parameter to ``push()`` is optional, so if you want a breadcrumb with no URL you can do so:

.. code-block:: php

    $breadcrumbs->push('Sample');

The ``$breadcrumb->url`` value will be ``null``.

The default Twitter Bootstrap templates provided render this with a CSS class of "active", the same as the last breadcrumb, because otherwise they default to black text not grey which doesn't look right.

*Note: Support for this was added to the Twitter Bootstrap templates in 2.1.0. Before this you would need to create a custom template.*


.. _custom-data:

================================================================================
 Custom data
================================================================================

*Added in 2.3.0.*

The ``push()`` method accepts an optional third parameter, ``$data`` - an array of arbitrary data to be passed to the breadcrumb, which you can use in your custom template. For example, if you wanted each breadcrumb to have an icon, you could do:

.. code-block:: php

    $breadcrumbs->push('Home', '/', ['icon' => 'home.png']);

The ``$data`` array's entries will be merged into the breadcrumb as properties, so you would access the icon as ``$breadcrumb->icon`` in your template, like this:

.. code-block:: html+php

    <li><a href="{{ $breadcrumb->url }}">
        <img src="/images/icons/{{ $breadcrumb->icon }}">
        {{ $breadcrumb->title }}
    </a></li>

Do not use the following keys in your data array, as they will be overwritten: ``title``, ``url``, ``first``, ``last``.


================================================================================
 Defining breadcrumbs in a different file
================================================================================

If you don't want to use ``routes/breadcrumbs.php`` (or ``app/Http/breadcrumbs.php`` in Laravel 5.2 and below), you can create a custom service provider to use instead of ``DaveJamesMiller\Breadcrumbs\ServiceProvider`` and override the ``registerBreadcrumbs()`` method:

.. code-block:: php

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

If you are creating your own package, simply load them from your service provider's ``boot()`` method:

.. code-block:: php

    class MyServiceProvider extends ServiceProvider
    {
        public function register() {}

        public function boot()
        {
            if (class_exists('Breadcrumbs'))
                require __DIR__ . '/breadcrumbs.php';
        }
    }


.. _switching-views:

================================================================================
 Switching views dynamically
================================================================================

You can change the view at runtime by calling:

.. code-block:: php

    Breadcrumbs::setView('view.name');

Or you can call ``Breadcrumbs::generate()`` and then load the view manually:

.. code-block:: html+php

    @include('_partials/breadcrumbs2', ['breadcrumbs' => Breadcrumbs::generate('category', $category)])


.. _current-route:

================================================================================
 Overriding the "current" route
================================================================================

If you call ``Breadcrumbs::render()`` or ``Breadcrumbs::generate()`` with no parameters, it will use the current route name and parameters by default (as returned by Laravel's ``Route::current()`` method).

You can override this by calling ``Breadcrumbs::setCurrentRoute($name, $param1, $param2...)`` or ``Breadcrumbs::setCurrentRouteArray($name, $params)``.


.. _array-parameters:

================================================================================
 Passing an array of parameters
================================================================================

*Added in 2.0.0.*

If the breadcrumb requires multiple parameters, you would normally pass them like this:

.. code-block:: php

    Breadcrumbs::render('name', $param1, $param2, $param3);
    Breadcrumbs::generate('name', $param1, $param2, $param3);
    $breadcrumbs->parent('name', $param1, $param2, $param3);

If you want to pass an array of parameters instead you can use these methods:

.. code-block:: php

    Breadcrumbs::renderArray('name', $params);
    Breadcrumbs::generateArray('name', $params);
    $breadcrumbs->parentArray('name', $params);


.. _exists:

================================================================================
 Checking if a breadcrumb exists
================================================================================

*Added in 2.2.0.*

By default an exception will be thrown if the breadcrumb doesn't exist, so you know to add it. If you want suppress this you can call the following methods instead:

- ``Breadcrumbs::renderIfExists()`` (returns an empty string)
- ``Breadcrumbs::renderIfExistsArray()`` (returns an empty string) (was ``renderArrayIfExists`` before 3.0.0)
- ``Breadcrumbs::generateIfExists()`` (returns an empty array)
- ``Breadcrumbs::generateIfExistsArray()`` (returns an empty array) (was ``generateArrayIfExists`` before 3.0.0)

Alternatively you can call ``Breadcrumbs::exists('name')``, which returns a boolean.
