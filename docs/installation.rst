################################################################################
 Installation
################################################################################

.. only:: html

    .. contents::
        :local:


================================================================================
 1. Install with Composer
================================================================================

.. code-block:: bash

    composer require davejamesmiller/laravel-breadcrumbs

This will update ``composer.json`` and install it into the ``vendor/`` directory.

(See the `Packagist website <https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs>`_ for a list of available version numbers and development releases.)


================================================================================
 2. Add to ``config/app.php``
================================================================================

Add the following to the ``providers`` array:

.. code-block:: php

    'providers' => [

        // ...

        'DaveJamesMiller\Breadcrumbs\ServiceProvider',

    ],

And this to the ``aliases`` array:

.. code-block:: php

    'aliases' => [

        // ...

        'Breadcrumbs' => 'DaveJamesMiller\Breadcrumbs\Facade',

    ],
