<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chirp.io</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="{{ URL::asset('/css/app.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('/css/custom.css') }}">
</head>
<body>
  @yield('content')
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="{{ URL::asset('js/app.js') }}"></script>
  <script src="https://use.fontawesome.com/dfa2b313d5.js"></script>
</body>
</html>
