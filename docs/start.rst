################################################################################
 Getting Started
################################################################################

.. only:: html

    .. contents::
        :local:


================================================================================
 1. Install Laravel Breadcrumbs
================================================================================

.. note::

    Laravel 5.0 or above is required -- use the `2.x version <https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.x>`_ for Laravel 4.


----------------------------------------
 Install with Composer
----------------------------------------

Run this at the command line:

.. code-block:: bash

    $ composer require davejamesmiller/laravel-breadcrumbs

This will both update ``composer.json`` and install the package into the ``vendor/`` directory.


----------------------------------------
 Add to ``config/app.php``
----------------------------------------

Add the service provider to ``providers``:

.. code-block:: php

    'providers' => [
        // ...
        'DaveJamesMiller\Breadcrumbs\ServiceProvider',
    ],

And add the facade to ``aliases``:

.. code-block:: php

    'aliases' => [
        // ...
        'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facade',
    ],


================================================================================
 2. Define your breadcrumbs
================================================================================

Create a file called ``app/Http/breadcrumbs.php`` that looks like this:

.. code-block:: php

    <?php

    // Home
    Breadcrumbs::register('home', function($breadcrumbs)
    {
        $breadcrumbs->push('Home', route('home'));
    });

    // Home > About
    Breadcrumbs::register('about', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('About', route('about'));
    });

    // Home > Blog
    Breadcrumbs::register('blog', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Blog', route('blog'));
    });

    // Home > Blog > [Category]
    Breadcrumbs::register('category', function($breadcrumbs, $category)
    {
        $breadcrumbs->parent('blog');
        $breadcrumbs->push($category->title, route('category', $category->id));
    });

    // Home > Blog > [Category] > [Page]
    Breadcrumbs::register('page', function($breadcrumbs, $page)
    {
        $breadcrumbs->parent('category', $page->category);
        $breadcrumbs->push($page->title, route('page', $page->id));
    });

See the :doc:`defining` section for more details.


.. _choose-template:

================================================================================
 3. Choose a template
================================================================================

By default a `Bootstrap <http://getbootstrap.com/components/#breadcrumbs>`_-compatible unordered list will be rendered, so if you're using Bootstrap 3 you can skip this step.

First initialise the config file by running this command:

.. code-block:: bash

    $ php artisan vendor:publish

Then open ``config/breadcrumbs.php`` and edit this line:

.. code-block:: php

    'view' => 'breadcrumbs::bootstrap3',

The possible values are:

- `Bootstrap 3 <http://getbootstrap.com/components/#breadcrumbs>`_: ``breadcrumbs::bootstrap3``
- `Bootstrap 2 <http://getbootstrap.com/2.3.2/components.html#breadcrumbs>`_: ``breadcrumbs::bootstrap2``
- The path to a custom view: e.g. ``_partials/breadcrumbs``

See the :doc:`templates` section for more details.


================================================================================
 4. Output the breadcrumbs
================================================================================

Finally, call ``Breadcrumbs::render()`` in the view template for each page, passing it the name of the breadcrumb to use and any additional parameters -- for example:

.. code-block:: html+php

    {!! Breadcrumbs::render('home') !!}

    {!! Breadcrumbs::render('category', $category) !!}

See the :doc:`output` section for other output options, and see :doc:`routing` for a way to link breadcrumb names to route names automatically.
