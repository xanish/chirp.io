<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Chirp.io') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('/css/custom.css') }}" rel="stylesheet">
  <link href="{{ asset('css/emojione.picker.css') }}" rel="stylesheet">
  <link href="{{ asset('fonts/icofont.ttf') }}" rel="application/x-font-ttf">
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container-fluid">
        <div class="navbar-header">

          <!-- Collapsed Hamburger -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!-- Branding Image -->
          @if (Auth::guest())
          <a class="navbar-brand" href="{{ url('/') }}">
              {{ config('app.name', 'Chirp.io') }}
          </a>
          @else
          <a class="navbar-brand" href="{{ url('/home') }}">
              {{ config('app.name', 'Chirp.io') }}
          </a>
          @endif
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
          <!-- Left Side Of Navbar -->
          <ul class="nav navbar-nav">
            &nbsp;
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="nav navbar-nav navbar-right">
            <!-- Authentication Links -->
            @if (Auth::guest())
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
            @else
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
              </a>

              <ul class="dropdown-menu" role="menu">
                  <li>
                      <a href="/edit-profile">Edit Profile</a>
                  </li>
                <li>
                  <a href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
              </li>
            </ul>
          </li>
          @endif
        </ul>
        {!! Form::open(['url' => 'search', 'class' => 'navbar-form navbar-right']) !!}
        <div class="input-group">
          {!! Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search', 'id' => 'search-bar']) !!}
          <span class="input-group-btn">
          <button class="btn btn-default" type="button"><span class="fa fa-search"></span></button>
        </span>
        </div>
        <div class="" id="search-results-dropdown" hidden="hidden">
          <div class="" id="serach-results">
            <ul class="list-unstyled" id="search-result-list">
              <li>
                <div class="row item-row">
                  <a id="search-page" href="/search">Show all results</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </nav>

  @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/jquery.jscroll.js') }}"></script>
<script src="{{ asset('js/emojione.picker.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCso5QRQXnxgph82Q8tV3oYj24SG56jnCc&libraries=places"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
<script src="https://use.fontawesome.com/dfa2b313d5.js"></script>
</body>
</html>
