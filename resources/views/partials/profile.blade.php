@extends('layouts.app')

@section('content')
<script>
var _username = {!! json_encode($user->username) !!}
</script>
    <div class="parallax" style="background-image: url('{{ asset(Config::get('constants.banners').$user->profile_banner) }}')"></div>
    <nav class="navbar default-color">
        <div class="container">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 hidden-xs">
                <img class="img-responsive img-circle profile-img"
                     src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}" alt="">
            </div>
            <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                <ul class="nav navbar-nav">
                    @if($path === $user->username)
                        <li class="active">
                    @else
                        <li>
                     @endif
                            <a href="/{{ $user->username }}">
                                <ul class="list-unstyled">
                                    <li>Tweets</li>
                                    <li>{{ $tweet_count }}</li>
                                </ul>
                            </a>
                        </li>
                        @if($path === $user->username.'/followers')
                            <li class="active">
                        @else
                            <li>
                        @endif
                            <a href="/{{ $user->username }}/followers">
                                <ul class="list-unstyled">
                                    <li>Followers</li>
                                    <li>{{ $follower_count }}</li>
                                </ul>
                            </a>
                        </li>
                            @if($path === $user->username.'/following')
                                <li class="active">
                            @else
                                <li>
                            @endif
                            <a href="/{{ $user->username }}/following">
                                <ul class="list-unstyled">
                                    <li>Following</li>
                                    <li>{{ $following_count }}</li>
                                </ul>
                            </a>
                        </li>
                </ul>
            </div>

        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12">
                <ul class="list-unstyled">
                    <li>
                        <h5>{{ $user->name }}</h5>
                    </li>
                    <li>
                        <h6>{{ '@'.$user->username }}</h6>
                    </li>
                    @if($user->birthdate)
                        <li>
                            <h6><i class="material-icons">cake</i> {{ $user->birthdate }}</h6>
                        </li>
                    @endif
                    @if ($user->city and $user->country)
                        <li>
                            <h6><i class="material-icons">place</i> {{ $user->city . ', ' . $user->country }}</h6>
                        </li>
                    @endif
                    <li>
                        <h6><i class="material-icons">date_range</i> Joined {{ $user->created_at->toFormattedDateString() }}</h6>
                    </li>
                    <li>
                      @if(Auth::guest())
                            <a class="btn btn-default" href="login">Login To Follow</a>
                        @elseif(Auth::user()->username == $user->username)
                            {{--Display nothing--}}
                        @elseif(Auth::user()->follows($user->id) == true)
                            <div class="card-action">
                                <button type="button" id="{{ $user->id }}" class="btn btn-danger unfollow">Unfollow</button>
                            </div>
                        @elseif(Auth::user()->follows($user->id) == false)
                            <div class="card-action">
                                <button type="button" id="{{ $user->id }}" class="btn btn-default follow">Follow</button>
                            </div>
                        @endif
                    </li>
                </ul>
            </div>

            @yield('data')

        </div>
    </div>

@endsection
