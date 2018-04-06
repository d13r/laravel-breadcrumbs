<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bulma Test</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.5.1/css/bulma.min.css">
    </head>
    <body>
        <section class="section">
            <div class="container">

                @include('_menu')
                <hr>

                <h1 class="title">Bulma</h1>

                <?php Config::set('breadcrumbs.view', 'breadcrumbs::bulma') ?>
                @include('_samples')

            </div>
        </section>
    </body>
</html>
