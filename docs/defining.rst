.. warning::

    Laravel Breadcrumbs is no longer maintained. Please see the `README <https://github.com/davejamesmiller/laravel-breadcrumbs/blob/master/README.rst>`_ for more details.

################################################################################
 Defining Breadcrumbs
################################################################################

Breadcrumbs will usually correspond to actions or types of page. For each breadcrumb you specify a name, the breadcrumb title and the URL to link it to. Since these are likely to change dynamically, you do this in a closure, and you pass any variables you need into the closure.

The following examples should make it clear:

.. only:: html

    .. contents::
        :local:


================================================================================
 Static pages
================================================================================

The most simple breadcrumb is probably going to be your homepage, which will look something like this:

.. code-block:: php

    Breadcrumbs::register('home', function($breadcrumbs) {
        $breadcrumbs->push('Home', route('home'));
    });

As you can see, you simply call ``$breadcrumbs->push($title, $url)`` inside the closure.

For generating the URL, you can use any of the standard Laravel URL-generation methods, including:

- ``url('path/to/route')`` (``URL::to()``)
- ``secure_url('path/to/route')``
- ``route('routename')`` or ``route('routename', 'param')`` or ``route('routename', ['param1', 'param2'])`` (``URL::route()``)
- ``action('controller@action')`` (``URL::action()``)
- Or just pass a string URL (``'http://www.example.com/'``)

This example would be rendered like this:

.. raw:: html

    <div class="highlight"><pre>
    Home
    </pre></div>

.. only:: not html

    ::

        Home


.. _defining-parents:

================================================================================
 Parent links
================================================================================

This is another static page, but this has a parent link before it:

.. code-block:: php

    Breadcrumbs::register('blog', function($breadcrumbs) {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Blog', route('blog'));
    });

It works by calling the closure for the ``home`` breadcrumb defined above.

It would be rendered like this:

.. raw:: html

    <div class="highlight"><pre>
    <a href="#">Home</a> / Blog
    </pre></div>

.. only:: not html

    ::

        Home > Blog

Note that the default template does not create a link for the last breadcrumb (the one for the current page), even when a URL is specified. You can override this by creating your own template - see :doc:`templates` for more details.


================================================================================
 Dynamic titles and links
================================================================================

This is a dynamically generated page pulled from the database:

.. code-block:: php

    Breadcrumbs::register('page', function($breadcrumbs, $page) {
        $breadcrumbs->parent('blog');
        $breadcrumbs->push($page->title, route('page', $page->id));
    });

The ``$page`` variable would simply be passed in from the view:

.. code-block:: html+php

    {!! Breadcrumbs::render('page', $page) !!}

It would be rendered like this:

.. raw:: html

    <div class="highlight"><pre>
    <a href="#">Home</a> / <a href="#">Blog</a> / Page Title
    </pre></div>

.. only:: not html

    ::

        Home > Blog > Page Title

**Tip:** You can pass multiple parameters if necessary.


================================================================================
 Nested categories
================================================================================

Finally if you have nested categories or other special requirements, you can call ``$breadcrumbs->push()`` multiple times:

.. code-block:: php

    Breadcrumbs::register('category', function($breadcrumbs, $category) {
        $breadcrumbs->parent('blog');

        foreach ($category->ancestors as $ancestor) {
            $breadcrumbs->push($ancestor->title, route('category', $ancestor->id));
        }

        $breadcrumbs->push($category->title, route('category', $category->id));
    });

Alternatively you could make a recursive function such as this:

.. code-block:: php

    Breadcrumbs::register('category', function($breadcrumbs, $category) {
        if ($category->parent)
            $breadcrumbs->parent('category', $category->parent);
        else
            $breadcrumbs->parent('blog');

        $breadcrumbs->push($category->title, route('category', $category->slug));
    });

Both would be rendered like this:

.. raw:: html

    <div class="highlight"><pre>
    <a href="#">Home</a> / <a href="#">Blog</a> / <a href="#">Grandparent Category</a> / <a href="#">Parent Category</a> / Category Title
    </pre></div>

.. only:: not html

    ::

        Home > Blog > Grandparent Category > Parent Category > Category Title
