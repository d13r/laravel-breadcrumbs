<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Foundation 6 Test</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://dhbhdrzi4tiry.cloudfront.net/cdn/sites/foundation.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel='stylesheet' type='text/css'>
    </head>
    <body>

        <div class="row column">

            <p>
                @include('_menu')
            </p>

            <h1>Foundation 6</h1>

            <?php Config::set('breadcrumbs.view', 'breadcrumbs::foundation6') ?>
            @include('_samples')

        </div>

    </body>
</html>
