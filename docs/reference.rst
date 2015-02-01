################################################################################
 API Reference
################################################################################

.. only:: html

    .. contents::
        :local:


================================================================================
 ``Breadcrumbs`` Facade
================================================================================

- ``Breadcrumbs::register($name, $callback)``
- ``Breadcrumbs::exists()`` (returns boolean) *(Added in 2.2.0)*
- ``Breadcrumbs::exists($name)`` (returns boolean) *(Added in 2.2.0)*
- ``Breadcrumbs::generate()`` (returns array) *(Added in 2.2.3)*
- ``Breadcrumbs::generate($name)`` (returns array)
- ``Breadcrumbs::generate($name, $param1, ...)`` (returns array)
- ``Breadcrumbs::generateArray($name, $params)`` (returns array) *(Added in 2.0.0)*
- ``Breadcrumbs::generateIfExists()`` (returns array) *(Added in 2.2.0)*
- ``Breadcrumbs::generateIfExists($name)`` (returns array) *(Added in 2.2.0)*
- ``Breadcrumbs::generateIfExists($name, $param1, ...)`` (returns array) *(Added in 2.2.0)*
- ``Breadcrumbs::generateArrayIfExists($name, $params)`` (returns array) *(Added in 2.2.0)*
- ``Breadcrumbs::render()`` (returns string) *(Added in 2.2.0)*
- ``Breadcrumbs::render($name)`` (returns string)
- ``Breadcrumbs::render($name, $param1, ...)`` (returns string)
- ``Breadcrumbs::renderArray($name, $params)`` (returns string) *(Added in 2.0.0)*
- ``Breadcrumbs::renderIfExists()`` (returns string) *(Added in 2.2.0)*
- ``Breadcrumbs::renderIfExists($name)`` (returns string) *(Added in 2.2.0)*
- ``Breadcrumbs::renderIfExists($name, $param1, ...)`` (returns string) *(Added in 2.2.0)*
- ``Breadcrumbs::renderArrayIfExists($name, $params)`` (returns string) *(Added in 2.2.0)*
- ``Breadcrumbs::setCurrentRoute($name)``
- ``Breadcrumbs::setCurrentRoute($name, $param1, ...)``
- ``Breadcrumbs::setCurrentRouteArray($name, $params)`` *(Added in 2.0.0)*
- ``Breadcrumbs::clearCurrentRoute()``
- ``Breadcrumbs::setView($view)``
- ``Breadcrumbs::getView()`` (returns string)

================================================================================
 Defining breadcrumbs
================================================================================

.. code-block:: php

    Breadcrumbs::register('name', function($breadcrumbs, $page) {
        // ...
    });


- ``$breadcrumbs->push($title)``
- ``$breadcrumbs->push($title, $url)``
- ``$breadcrumbs->push($title, $url, $data)`` *(Added in 2.3.0)*
- ``$breadcrumbs->parent($name)``
- ``$breadcrumbs->parent($name, $param1, ...)``
- ``$breadcrumbs->parentArray($name, $params)`` *(Added in 2.0.0)*

================================================================================
 In the view (template)
================================================================================

- ``$breadcrumbs`` (array), contains:
    - ``$breadcrumb->title`` (string)
    - ``$breadcrumb->url`` (string or null)
    - ``$breadcrumb->first`` (boolean)
    - ``$breadcrumb->last`` (boolean)
    - ``$breadcrumb->custom_attribute_name`` (mixed - any custom data attributes you specified)
