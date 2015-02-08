################################################################################
 Changelog
################################################################################

.. role:: date
    :class: changelog-date

.. role:: future
    :class: changelog-future

.. role:: strikethrough
    :class: strikethrough


Laravel Breadcrumbs uses `Semantic Versioning <http://semver.org/>`_.


.. ================================================================================
..  v3.0.1_ :future:`(Unreleased)`
.. ================================================================================


================================================================================
 v3.0.0_ :date:`(8 Feb 2015)`
================================================================================

- Add Laravel 5 support (`#62`_)
- Change view namespace from ``laravel-breadcrumbs::`` to ``breadcrumbs::``
- Change Bootstrap 3 template from ``<ul>`` to ``<ol>`` to match the `documentation <http://getbootstrap.com/components/#breadcrumbs>`_
- Move documentation from GitHub (Markdown) to `Read The Docs <https://readthedocs.org/>`_ (reStructuredText/`Sphinx <http://sphinx-doc.org/>`_)
- Greatly improve unit & integration tests (largely thanks to `Testbench <https://github.com/orchestral/testbench>`_)
- Fix issue that prevented non-deferred service providers referencing Breadcrumbs (`#39`_) by making Breadcrumbs non-deferred also
- Rename ``generateArrayIfExists()`` to ``generateIfExistsArray()``
- Rename ``renderArrayIfExists()`` to ``renderIfExistsArray()``
- Remove ``$breadcrumbs->get()`` and ``$breadcrumbs->set()`` methods from Generator class (they were never used nor documented)
- Remove ``Breadcrumbs::getView()``
- Switch from PSR-0 to PSR-4 file naming

.. _v3.0.0: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.0.0
.. _#39: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/39
.. _#62: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/62


----------------------------------------
 Upgrading from 2.x to 3.x
----------------------------------------

- `Upgrade to Laravel 5 <http://laravel.com/docs/5.0/upgrade#upgrade-5.0>`_
- Move ``app/breadcrumbs.php`` to ``app/Http/breadcrumbs.php``
- Move ``app/config/packages/davejamesmiller/laravel-breadcrumbs/config.php`` to ``config/breadcrumbs.php`` (if used)

The following changes are optional because there are shims in place:

- In the config file, replace ``laravel-breadcrumbs::`` with ``breadcrumbs::``
- Replace any calls to ``Breadcrumbs::generateArrayIfExists()`` with ``Breadcrumbs::generateIfExistsArray()``
- Replace any calls to ``Breadcrumbs::renderArrayIfExists()`` with ``Breadcrumbs::renderIfExistsArray()``

.. note::

    Laravel 4 and PHP 5.3 are no longer supported -- please continue to use the `2.x branch <https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.x>`_ if you use them.


================================================================================
 v2.3.1_ :date:`(8 Feb 2015)`
================================================================================

- Fix issue that prevented non-deferred service providers referencing Breadcrumbs (`#39`_) by making Breadcrumbs non-deferred also (backported from 3.0.0)

.. _v2.3.1: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/3.0.0


================================================================================
 v2.3.0_ :date:`(26 Oct 2014)`
================================================================================

- Add ``$data`` parameter to ``$breadcrumb->push()`` to allow for arbitrary data (`#34`_, `#35`_, `#55`_, `#56`_)

.. _v2.3.0: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.3.0
.. _#34: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/34
.. _#35: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/35
.. _#55: https://github.com/davejamesmiller/laravel-breadcrumbs/pull/55
.. _#56: https://github.com/davejamesmiller/laravel-breadcrumbs/pull/56
.. _3a0afc2: https://github.com/laravel/framework/commit/3a0afc20f25ad3bed640ff1a14957f972d123cf7


================================================================================
 v2.2.3_ :date:`(10 Sep 2014)`
================================================================================

- Fix ``Breadcrumbs::generate()`` with no parameters so it uses the current route, like ``Breadcrumbs::render()`` does (`#46`_)

.. _v2.2.3: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.2.3
.. _#46: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/46


================================================================================
 v2.2.2_ :date:`(3 Aug 2014)`
================================================================================

- Support for Laravel's ``App::missing()`` method when using automatic route detection (`#40`_, `#41`_)

.. _v2.2.2: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.2.2
.. _#40: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/40
.. _#41: https://github.com/davejamesmiller/laravel-breadcrumbs/pull/41


================================================================================
 v2.2.1_ :date:`(19 May 2014)`
================================================================================

- Laravel 4.2 support (`#21`_, `#28`_)

.. _v2.2.1: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.2.1
.. _#21: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/21
.. _#28: https://github.com/davejamesmiller/laravel-breadcrumbs/pull/28


================================================================================
 v2.2.0_ :date:`(26 Jan 2014)`
================================================================================

- Add ``Breadcrumbs::exists()``, ``renderIfExists()``, ``renderArrayIfExists()`` (`#22`_)
- Use the current route name & parameters by default so you don't have to specify them in the view (as long as you use consistent names) (`#16`_, `#24`_)

.. _v2.2.0: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.2.0
.. _#16: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/16
.. _#22: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/22
.. _#24: https://github.com/davejamesmiller/laravel-breadcrumbs/pull/24


================================================================================
 v2.1.0_ :date:`(16 Oct 2013)`
================================================================================

- Add support for non-linked breadcrumbs to the Twitter Bootstrap templates (`#20`_)

.. _v2.1.0: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.1.0
.. _#20: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/20


================================================================================
 v2.0.0_ :date:`(28 Sep 2013)`
================================================================================

- Add Twitter Bootstrap v3 template (`#7`_)
- Twitter Bootstrap v3 is now the default template
- Support for passing arrays into ``render()``, ``generate()`` and ``parent()`` (**not backwards-compatible**) (`#8`_)

  - Split ``Breadcrumbs::render()`` into ``render($name, $arg1, $arg2)`` and ``renderArray($name, $params)``
  - Split ``Breadcrumbs::generate()`` into ``generate($name, $arg1, $arg2)`` and ``generateArray($name, $params)``
  - Split ``$breadcrumbs->parent()`` into ``parent($name, $arg1, $arg2)`` and ``parentArray($name, $params)``

- Set view name in config file instead of in ``breadcrumbs.php`` (`#10`_, `#11`_)
- Simplify class names (`#15`_)
- Add unit tests

.. _v2.0.0: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/2.0.0
.. _#7: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/7
.. _#8: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/8
.. _#10: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/10
.. _#11: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/11
.. _#15: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/15


----------------------------------------
 Upgrading from 1.x to 2.x
----------------------------------------

- In ``app/config/app.php`` change ``DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider`` to ``DaveJamesMiller\Breadcrumbs\ServiceProvider``
- In ``app/config/app.php`` change ``DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs`` to ``DaveJamesMiller\Breadcrumbs\Facade``
- The default template was changed from Bootstrap 2 to Bootstrap 3. See :ref:`Choose a template <choose-template>` if you need to switch it back.

The following internal changes will not affect most people but if you have any problems please be aware of the following:

- The view namespace was changed from ``breadcrumbs`` to ``laravel-breadcrumbs`` to match the Composer project name.
- The Bootstrap 2 template name was changed from ``breadcrumbs::bootstrap`` to ``laravel-breadcrumbs::bootstrap2``.
- If you pass arrays into any of the methods, please read the following section:


Passing arrays into ``render()``, ``generate()`` and ``parent()``
.................................................................

In **version 1.x** you could pass an array into each of these methods and it was split up into several parameters. For example:

.. code-block:: php

    // If this breadcrumb is defined:
    Breadcrumbs::register('page', function($breadcrumbs, $param1, $param2)
    {
        $breadcrumbs->push($param1, $param2);
    });

    // Then this:
    Breadcrumbs::render('page', ['param1', 'param2']);

    // Was equivalent to this:
    Breadcrumbs::render('page', 'param1', 'param2');

    // But to pass an array as the first parameter you would have to do this instead:
    Breadcrumbs::render('page', [['param1A', 'param1B']]);

This means you couldn't pass an array as the first parameter unless you wrapped all parameters in another array (issue `#8`_).

In **version 2.x** this has been split into two methods:

.. code-block:: php

    // Now this:
    Breadcrumbs::renderArray('page', ['param1', 'param2']);

    // Is equivalent to this:
    Breadcrumbs::render('page', 'param1', 'param2');

    // And this only passes a single parameter (an array) to the callback:
    Breadcrumbs::render('page', ['param1A', 'param1B']);

Similarly ``Breadcrumbs::generateArray()`` and ``$breadcrumbs->parentArray()`` methods are available, which take a single array argument.


================================================================================
 v1.0.1_ :date:`(13 Jul 2013)`
================================================================================

- Fix for PHP 5.3 compatibility (`#3`_)

.. _v1.0.1: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/1.0.1
.. _#3: https://github.com/davejamesmiller/laravel-breadcrumbs/issues/3


================================================================================
 v1.0.0_ :date:`(25 May 2013)`
================================================================================

.. _v1.0.0: https://github.com/davejamesmiller/laravel-breadcrumbs/tree/1.0.0

- Initial release
