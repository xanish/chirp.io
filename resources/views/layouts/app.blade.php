<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mdb.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/emojionearea.css') }}" rel="stylesheet">
    <link href="{{ asset('font/icofont.ttf') }}" rel="application/x-font-ttf">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">
    @if($color != 'default' and $color != '')
        <link rel="stylesheet" href="{{ asset('css/'.$color.'.css') }}">
    @endif
</head>
<body>
    <div id="app">
        @include('partials.navbar')

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/mdb.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/emojionearea.js') }}"></script>
    <script src="{{ asset('js/lightbox.js') }}" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCso5QRQXnxgph82Q8tV3oYj24SG56jnCc&libraries=places"></script>
    <script src="https://use.fontawesome.com/dfa2b313d5.js"></script>
</body>
</html>
