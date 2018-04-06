<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Materialize Test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
    </head>
    <body>

        <div class="container">
            <div class="row">

                <p>
                    @include('_menu')
                </p>

                <h1>Materialize</h1>

                <?php Config::set('breadcrumbs.view', 'breadcrumbs::materialize') ?>
                @include('_samples')

            </div>
        </div>

    </body>
</html>
