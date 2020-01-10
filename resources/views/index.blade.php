<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(config('under-construction.lock_robots'))
    <meta name="robots" content="noindex,nofollow">
    @endif

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <style>
        html, body {
            font-family: 'Raleway', sans-serif;
            overflow-y: hidden;
        }
    </style>
</head>
<body>

<div id="app">
    <under-construction
            :title="{{ @json_encode($title) }}"
            :back-button="{{ @json_encode($backButton) }}"
            :show-button="{{ @json_encode($showButton) }}"
            :hide-button="{{ @json_encode($hideButton) }}"
            :show-loader="{{ @json_encode($showLoader) }}"
            :total-digits="{{ @json_encode($totalDigits) }}"
            :redirect-url="{{ @json_encode($redirectUrl) }}">
    </under-construction>
</div>

<script src="/{{config('under-construction.route-prefix')}}/js"></script>
</body>
</html>
