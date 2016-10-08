.. warning::

    Laravel Breadcrumbs is no longer maintained. Please see the `README <https://github.com/davejamesmiller/laravel-breadcrumbs/blob/master/README.rst>`_ for more details.

################################################################################
 API Reference
################################################################################

.. only:: html

    .. contents::
        :local:


================================================================================
 ``Breadcrumbs`` Facade
================================================================================

========================================================  ==========  ==========  ============================
 Method                                                    Returns     Added in    Docs
========================================================  ==========  ==========  ============================
 ``Breadcrumbs::register($name, $callback)``               *(none)*    1.0.0       :doc:`Defining <defining>`
 ``Breadcrumbs::exists()``                                 boolean     2.2.0       :doc:`Route-bound <routing>`
 ``Breadcrumbs::exists($name)``                            boolean     2.2.0       :ref:`Exists <exists>`
 ``Breadcrumbs::generate()``                               array       2.2.3       :doc:`Route-bound <routing>`
 ``Breadcrumbs::generate($name)``                          array       1.0.0       :ref:`Switching views <switching-views>`
 ``Breadcrumbs::generate($name, $param1, ...)``            array       1.0.0       :ref:`Switching views <switching-views>`
 ``Breadcrumbs::generateArray($name, $params)``            array       2.0.0       :ref:`Array params <array-parameters>`
 ``Breadcrumbs::generateIfExists()``                       array       2.2.0       :doc:`Route-bound <routing>`
 ``Breadcrumbs::generateIfExists($name)``                  array       2.2.0       :ref:`Exists <exists>`
 ``Breadcrumbs::generateIfExists($name, $param1, ...)``    array       2.2.0       :ref:`Exists <exists>`
 ``Breadcrumbs::generateIfExistsArray($name, $params)``    array       3.0.0       :ref:`Exists <exists>`
 ``Breadcrumbs::render()``                                 string      2.2.0       :doc:`Route-bound <routing>`
 ``Breadcrumbs::render($name)``                            string      1.0.0       :doc:`Output <output>`
 ``Breadcrumbs::render($name, $param1, ...)``              string      1.0.0       :doc:`Output <output>`
 ``Breadcrumbs::renderArray($name, $params)``              string      2.0.0       :ref:`Array params <array-parameters>`
 ``Breadcrumbs::renderIfExists()``                         string      2.2.0       :doc:`Route-bound <routing>`
 ``Breadcrumbs::renderIfExists($name)``                    string      2.2.0       :ref:`Exists <exists>`
 ``Breadcrumbs::renderIfExists($name, $param1, ...)``      string      2.2.0       :ref:`Exists <exists>`
 ``Breadcrumbs::renderIfExistsArray($name, $params)``      string      3.0.0       :ref:`Exists <exists>`
 ``Breadcrumbs::setCurrentRoute($name)``                   *(none)*    2.2.0       :ref:`Current route <current-route>`
 ``Breadcrumbs::setCurrentRoute($name, $param1, ...)``     *(none)*    2.2.0       :ref:`Current route <current-route>`
 ``Breadcrumbs::setCurrentRouteArray($name, $params)``     *(none)*    2.2.0       :ref:`Current route <current-route>`
 ``Breadcrumbs::clearCurrentRoute()``                      *(none)*    2.2.0
 ``Breadcrumbs::setView($view)``                           *(none)*    1.0.0       :ref:`Switching views <switching-views>`
========================================================  ==========  ==========  ============================

`Source <https://github.com/davejamesmiller/laravel-breadcrumbs/blob/develop/src/Manager.php>`__


================================================================================
 Defining breadcrumbs
================================================================================

.. code-block:: php

    Breadcrumbs::register('name', function($breadcrumbs, $page) {
        // ...
    });


========================================================  ==========  ==========  ============================
 Method                                                    Returns     Added in    Docs
========================================================  ==========  ==========  ============================
 ``$breadcrumbs->push($title)``                            *(none)*    1.0.0       :ref:`No URL <no-url>`
 ``$breadcrumbs->push($title, $url)``                      *(none)*    1.0.0       :doc:`Defining <defining>`
 ``$breadcrumbs->push($title, $url, $data)``               *(none)*    2.3.0       :ref:`Custom data <custom-data>`
 ``$breadcrumbs->parent($name)``                           *(none)*    1.0.0       :ref:`Parent links <defining-parents>`
 ``$breadcrumbs->parent($name, $param1, ...)``             *(none)*    1.0.0       :ref:`Parent links <defining-parents>`
 ``$breadcrumbs->parentArray($name, $params)``             *(none)*    2.0.0       :ref:`Array parameters <array-parameters>`
========================================================  ==========  ==========  ============================

`Source <https://github.com/davejamesmiller/laravel-breadcrumbs/blob/develop/src/Generator.php>`__


================================================================================
 In the view (template)
================================================================================

``$breadcrumbs`` (array), contains:

========================================================  ================  ==========  ============================
 Variable                                                  Type              Added in    Docs
========================================================  ================  ==========  ============================
 ``$breadcrumb->title``                                    string            1.0.0       :ref:`View data <view-data>`
 ``$breadcrumb->url``                                      string or null    1.0.0       :ref:`View data <view-data>`
 ``$breadcrumb->first``                                    boolean           1.0.0       :ref:`View data <view-data>`
 ``$breadcrumb->last``                                     boolean           1.0.0       :ref:`View data <view-data>`
 ``$breadcrumb->custom_attribute_name``                    mixed             2.3.0       :ref:`Custom data <custom-data>`
========================================================  ================  ==========  ============================
