################################################################################
 Route-Bound Breadcrumbs
################################################################################

In normal usage you must call ``Breadcrumbs::render($name, $params...)`` to render the breadcrumbs on every page. If you prefer, you can name your breadcrumbs the same as your routes and avoid this duplication.

.. only:: html

    .. contents::
        :local:


================================================================================
 Setup
================================================================================

----------------------------------------
 Name your routes
----------------------------------------

Make sure each of your routes has a name (``'as'`` parameter). For example (``app/Http/routes.php``):

.. code-block:: php

    // Home
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

    // Home > [Page]
    Route::get('/page/{id}', ['as' => 'page', 'uses' => 'PageController@show']);

For more details see `Named Routes <http://laravel.com/docs/routing#named-routes>`_ in the Laravel documentation.


----------------------------------------
 Name your breadcrumbs to match
----------------------------------------

For each route, create a breadcrumb with the same name. For example (``app/Http/routes.php``):

.. code-block:: php

    // Home
    Breadcrumbs::register('home', function($breadcrumbs) {
        $breadcrumbs->push('Home', route('home'));
    });

    // Home > [Page]
    Breadcrumbs::register('page', function($breadcrumbs, $id)
    {
        $page = Page::findOrFail($id);
        $breadcrumbs->parent('home');
        $breadcrumbs->push($page->title, route('page', $page->id));
    });


----------------------------------------
 Output breadcrumbs in your layout
----------------------------------------

Call ``Breadcrumbs::render()`` with no parameters in your layout file (e.g. ``resources/views/app.blade.php``):

.. code-block:: html+php

    {!! Breadcrumbs::render() !!}

This will automatically output breadcrumbs corresponding to the current route.

It will throw an exception if the breadcrumb doesn't exist, to remind you to create one. To prevent this behaviour, change it to:

.. code-block:: html+php

    {!! Breadcrumbs::renderIfExists() !!}


================================================================================
 Route model binding
================================================================================

Laravel Breadcrumbs uses the same model binding as the controller. For example:

.. code-block:: php

    // app/Http/routes.php
    Route::model('page', 'Page');
    Route::get('/page/{page}', ['uses' => 'PageController@show', 'as' => 'page']);

.. code-block:: php

    // app/Http/Controllers/PageController.php
    class PageController extends Controller {
        public function show($page)
        {
            return view('page/show', ['page' => $page]);
        }
    }

.. code-block:: php

    // app/Http/breadcrumbs.php
    Breadcrumbs::register('page', function($breadcrumbs, $page)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push($page->title, route('page', $page->id));
    });

This makes your code less verbose and more efficient by only loading the page from the database once.

For more details see `Route Model Binding <http://laravel.com/docs/routing#route-model-binding>`_ in the Laravel documentation.


================================================================================
 Resourceful controllers
================================================================================

Laravel automatically creates route names for resourceful controllers, e.g. ``photo.index``, which you can use when defining your breadcrumbs. For example:

.. code-block:: php

    // app/Http/routes.php
    Route::resource('photo', 'PhotoController');

.. code-block:: bash

    $ php artisan route:list
    +--------+----------+--------------------+---------------+-------------------------+------------+
    | Domain | Method   | URI                | Name          | Action                  | Middleware |
    +--------+----------+--------------------+---------------+-------------------------+------------+
    |        | GET|HEAD | photo              | photo.index   | PhotoController@index   |            |
    |        | GET|HEAD | photo/create       | photo.create  | PhotoController@create  |            |
    |        | POST     | photo              | photo.store   | PhotoController@store   |            |
    |        | GET|HEAD | photo/{photo}      | photo.show    | PhotoController@show    |            |
    |        | GET|HEAD | photo/{photo}/edit | photo.edit    | PhotoController@edit    |            |
    |        | PUT      | photo/{photo}      | photo.update  | PhotoController@update  |            |
    |        | PATCH    | photo/{photo}      |               | PhotoController@update  |            |
    |        | DELETE   | photo/{photo}      | photo.destroy | PhotoController@destroy |            |
    +--------+----------+--------------------+---------------+-------------------------+------------+

.. code-block:: php

    // app/Http/breadcrumbs.php

    // Photos
    Breadcrumbs::register('photo.index', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Photos', route('photo.index'));
    });

    // Photos > Upload Photo
    Breadcrumbs::register('photo.create', function($breadcrumbs)
    {
        $breadcrumbs->parent('photo.index');
        $breadcrumbs->push('Upload Photo', route('photo.create'));
    });

    // Photos > [Photo Name]
    Breadcrumbs::register('photo.show', function($breadcrumbs, $photo)
    {
        $breadcrumbs->parent('photo.index');
        $breadcrumbs->push($photo->title, route('photo.show', $photo->id));
    });

    // Photos > [Photo Name] > Edit Photo
    Breadcrumbs::register('photo.edit', function($breadcrumbs, $photo)
    {
        $breadcrumbs->parent('photo.show', $photo);
        $breadcrumbs->push('Edit Photo', route('photo.edit', $photo->id));
    });

For more details see `RESTful Resource Controllers <http://laravel.com/docs/controllers#restful-resource-controllers>`_ in the Laravel documentation.


================================================================================
 Implicit controllers
================================================================================

To use implicit controllers, you must specify names for each route. For example:

.. code-block:: php

    // app/Http/routes.php
    Route::controller('auth', 'Auth\AuthController', [
        'getRegister' => 'auth.register',
        'getLogin'    => 'auth.login',
    ]);

(You don't need to provide route names for actions that redirect and never display a view - e.g. most POST views.)

For more details see `Implicit Controllers <http://laravel.com/docs/controllers#implicit-controllers>`_ in the Laravel documentation.
