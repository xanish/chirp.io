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
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-3 col-xs-4">
                <img class="img-responsive center-block" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-9 col-xs-8">
                <div class="text-center">
                    <h4>{{ $user->name }}</h4>
                    <h5>{{ '@'.$user->username}}</h5>
                    @if ($user->city and $user->country)
                    <h6><span class="fa fa-map-marker"></span> {{ $user->city . ', ' . $user->country }}</h6>
                    @endif
                    @if ($user->birthdate)
                    <h6><span class="fa fa-birthday-cake"></span> {{ $user->birthdate }}</h6>
                    @endif
                    @if ($user->created_at)
                    <h6><span class="fa fa-calendar-o"></span> {{ $user->created_at->diffForHumans() }}</h6>
                    @endif
                    @if ($showFollows == 'true')
                    {!! Form::open(['method' => 'PATCH', 'url' => '/following/'.$user->username]) !!}
                    {!! Form::hidden('following', $user->username) !!}
                    <button type="submit" class="btn btn-danger btn-rounded" onclick="this.disabled=true;this.innerHTML='Unfollowing..'; this.form.submit();">Unfollow</button>
                    {!! Form::close() !!}
                    @elseif ($showFollows == 'false')
                    {!! Form::open(['method' => 'POST', 'url' => '/following']) !!}
                    {!! Form::hidden('following', $user->username) !!}
                    <button type="submit" class="btn btn-primary btn-rounded" onclick="this.disabled=true;this.innerHTML='Following..'; this.form.submit();">Follow</button>
                    {!! Form::close() !!}
                    @elseif ($showFollows == '')
                    <a href="/login" class="btn btn-primary btn-rounded">Login to follow</a>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" id="count-bar">
            <div class="row" id="nav-links">
                <a href="/{{ $user->username }}">
                    <div class="col-lg-1 col-md-2 col-sm-4 col-xs-4">
                        <h3 class="font-size-14 no-margins">Tweets</h3>
                        <span class="colored-text-nav-links font-size-14">{{ $tweet_count }}</span>
                    </div>
                </a>
                @if ($append)
                <a href="/followers/{{ $user->username }}">
                    <div class="col-lg-1 col-md-2 col-sm-4 col-xs-4">
                        <h3 class="font-size-14 no-margins">Followers</h3>
                        <span class="colored-text-nav-links font-size-14">{{ $follower_count }}</span>
                    </div>
                </a>
                <a href="/following/{{ $user->username }}">
                    <div class="col-lg-1 col-md-2 col-sm-4 col-xs-4">
                        <h3 class="font-size-14 no-margins">Following</h3>
                        <span class="colored-text-nav-links font-size-14">{{ $following_count }}</span>
                    </div>
                </a>
                @else
                <a href="/followers">
                    <div class="col-lg-1 col-md-2 col-sm-4 col-xs-4">
                        <h3 class="font-size-14 no-margins">Followers</h3>
                        <span class="colored-text-nav-links font-size-14">{{ $follower_count }}</span>
                    </div>
                </a>
                <a href="/following">
                    <div class="col-lg-1 col-md-2 col-sm-4 col-xs-4">
                        <h3 class="font-size-14 no-margins">Following</h3>
                        <span class="colored-text-nav-links font-size-14">{{ $following_count }}</span>
                    </div>
                </a>
                @endif
            </div>
        </div>
        @yield('content')
    </div>
    <div class="footer footer-fixed visible-xs">
        {!! Form::open(['method' => 'POST', 'url' => 'tweet']) !!}
            <div class="input-group">
              <input type="text" class="form-control styled-input" placeholder="What's happening!" name="tweet_text" id="tweetbox">
              <span class="input-group-btn">
                <button class="btn btn-primary styled-input" type="button" onclick="getTweet()" id="tweet-button" type="submit"><i class="icofont icofont-animal-woodpecker"></i></button>
              </span>
            </div>
        {!! Form::close() !!}
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/jquery.jscroll.js') }}"></script>
<script src="https://use.fontawesome.com/dfa2b313d5.js"></script>
</body>
</html>
