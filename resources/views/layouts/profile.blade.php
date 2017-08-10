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
              <h3>{{ $user->name }}</h3>
              <h4>{{ '@'.$user->username}}</h4>
              @if ($user->city and $user->country)
              <h5><span class="fa fa-map-marker"></span> {{ $user->city . ', ' . $user->country }}</h5>
              @endif
              @if ($user->birthdate)
              <h5><span class="fa fa-birthday-cake"></span> {{ $user->birthdate }}</h5>
              @endif
              @if ($user->created_at)
              <h5><span class="fa fa-calendar-o"></span> {{ $user->created_at->diffForHumans() }}</h5>
              @endif
          </div>
      </div>
      <div class="col-lg-10">
          <div class="row" id="nav-links">
              @if ($append)
              <a href="/{{ $user->username }}/followers">
                  <div class="col-lg-1">
                      <h3 class="font-size-14 no-margins">Followers</h3>
                      <span class="colored-text-nav-links font-size-14">{{ $user->follower_count }}</span>
                  </div>
              </a>
              <a href="/{{ $user->username }}/following">
                  <div class="col-lg-1">
                      <h3 class="font-size-14 no-margins">Following</h3>
                      <span class="colored-text-nav-links font-size-14">{{ $user->following_count }}</span>
                  </div>
              </a>
              @else
              <a href="/followers">
                  <div class="col-lg-1">
                      <h3 class="font-size-14 no-margins">Followers</h3>
                      <span class="colored-text-nav-links font-size-14">{{ $user->follower_count }}</span>
                  </div>
              </a>
              <a href="/following">
                  <div class="col-lg-1">
                      <h3 class="font-size-14 no-margins">Following</h3>
                      <span class="colored-text-nav-links font-size-14">{{ $user->following_count }}</span>
                  </div>
              </a>
              @endif
          </div>
          <div class="row padding-20">
              @yield('content')
          </div>
      </div>
  </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://use.fontawesome.com/dfa2b313d5.js"></script>
</body>
</html>
