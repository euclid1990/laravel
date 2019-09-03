<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        <div id="app"></div>
        <script type="text/javascript" charset="utf-8">
            window.config = {
                lang: "{{ str_replace('_', '-', app()->getLocale()) }}",
                configExport: {!! json_encode(config('common.export')) !!},
            };
        </script>
    </body>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</html>
