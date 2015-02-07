################################################################################
 Custom Templates
################################################################################

.. only:: html

    .. contents::
        :local:


================================================================================
 Create a view
================================================================================

To customise the HTML, create your own view file (e.g. ``resources/views/_partials/breadcrumbs.blade.php``) like this:

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

(See the `views/ directory <https://github.com/davejamesmiller/laravel-breadcrumbs/tree/master/views>`_ for the built-in templates.)


.. _view-data:

----------------------------------------
 View data
----------------------------------------

The view will receive an array called ``$breadcrumbs``.

Each breadcrumb is an object with the following keys:

- ``title`` - The title you set above
- ``url`` - The URL you set above
- ``first`` - ``true`` for the first breadcrumb (top level), ``false`` otherwise
- ``last`` - ``true`` for the last breadcrumb (current page), ``false`` otherwise
- Additional keys for each item in ``$data`` (see :ref:`custom-data`)


================================================================================
 Update the config
================================================================================

Then update your config file (``config/breadcrumbs.php``) with the custom view name, e.g.:

.. code-block:: php

    'view' => '_partials/breadcrumbs',
