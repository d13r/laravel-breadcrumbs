.. warning::

    Laravel Breadcrumbs is no longer maintained. Please see the `README <https://github.com/davejamesmiller/laravel-breadcrumbs/blob/master/README.rst>`_ for more details.

################################################################################
 Outputting Breadcrumbs
################################################################################

Call ``Breadcrumbs::render()`` in the view template for each page, passing it the name of the breadcrumb to use and any additional parameters.

.. only:: html

    .. contents::
        :local:


================================================================================
 With Blade
================================================================================

In the page (e.g. ``resources/views/home.blade.php``):

.. code-block:: html+php

    {!! Breadcrumbs::render('home') !!}

Or with a parameter:

.. code-block:: html+php

    {!! Breadcrumbs::render('category', $category) !!}


================================================================================
 With Blade layouts and @section
================================================================================

In the page (e.g. ``resources/views/home.blade.php``):

.. code-block:: html+php

    @extends('layout.name')

    @section('breadcrumbs', Breadcrumbs::render('home'))

In the layout (e.g. ``resources/views/app.blade.php``):

.. code-block:: html+php

    @yield('breadcrumbs')


================================================================================
 Pure PHP (without Blade)
================================================================================

In the page (e.g. ``resources/views/home.php``):

.. code-block:: html+php

    <?= Breadcrumbs::render('home') ?>

Or use the long-hand syntax if you prefer:

.. code-block:: html+php

    <?php echo Breadcrumbs::render('home') ?>
