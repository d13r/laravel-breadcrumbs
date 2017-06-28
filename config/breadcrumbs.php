<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Name
    |--------------------------------------------------------------------------
    |
    | Choose a view to display when Breadcrumbs::render() is called.
    | Built in templates are:
    |
    | - 'breadcrumbs::bootstrap3' - Twitter Bootstrap v3
    | - 'breadcrumbs::bootstrap2' - Twitter Bootstrap v2
    |
    | Or a custom view, e.g. '_partials/breadcrumbs'.
    |
    */

    'view' => 'breadcrumbs::bootstrap3',

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs File(s)
    |--------------------------------------------------------------------------
    |
    | The file(s) where breadcrumbs are defined. e.g.
    |
    | - base_path('routes/breadcrumbs.php')
    | - glob(base_path('breadcrumbs/*.php'))
    |
    */

    'files' => base_path('routes/breadcrumbs.php'),

];
