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
  <link href="{{ asset('css/icofont.css') }}" rel="stylesheet">
  <link href="{{ asset('fonts/icofont.ttf') }}" rel="stylesheet">
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
          <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Chirp.io') }}
          </a>
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
                  <a href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
              </li>
              <li>
                <a href="#">
                Edit Profile
              </a>
            </li>
            </ul>
          </li>
          @endif
        </ul>
      </div>
    </div>
  </nav>
  <div class="container container-wide">
      <div class="col-lg-2">
          <img src="http://via.placeholder.com/220x220/6255b2/ffffff" alt="">
          <div class="text-center">
              <h3>Name</h3>
              <h4>@username</h4>
              <h5><span class="fa fa-map-marker"></span> Mumbai, India</h5>
              <h5><span class="fa fa-calendar-o"></span> 2016</h5>
          </div>
      </div>
      <div class="col-lg-10">
          <div class="row" id="nav-links">
              <div class="col-lg-1">
                  <h3 class="font-size-14 no-margins">Followers</h3>
                  <span class="colored-text-nav-links font-size-14">5</span>
              </div>
              <div class="col-lg-1">
                  <h3 class="font-size-14 no-margins">Following</h3>
                  <span class="colored-text-nav-links font-size-14">12</span>
              </div>
          </div>
          <div class="row">
              <div class="col-lg-4">
                  <div class="card">
                      <div class="profile-img">

                      </div>
                      <div class="personal-details">

                      </div>
                  </div>
              </div>

          </div>
      </div>
  </div>
  @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://use.fontawesome.com/dfa2b313d5.js"></script>
</body>
</html>
