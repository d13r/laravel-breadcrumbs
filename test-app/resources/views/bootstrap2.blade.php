<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Laravel Breadcrumbs Test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/2.3.2/css/bootstrap.min.css">
    </head>
    <body>

        <div class="container">

            <p>
                @include('_menu')
            </p>

            <h1>Twitter Bootstrap 2</h1>

            <?php Config::set('breadcrumbs.view', 'breadcrumbs::bootstrap2') ?>
            @include('_samples')

        </div>

    </body>
</html>
