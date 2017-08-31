@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="card">
                    <div class="card-image">
                        <div class="view overlay hm-white-slight z-depth-1">
                            <img src="{{ asset(Config::get('constants.banners').$user->profile_banner) }}"
                                 class="img-responsive" alt="">
                            <a href="#">
                                <div class="mask waves-effect"></div>
                            </a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <img src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}"
                                 class="img-responsive img-circle img-floating"></img>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <a href="/{{ $user->username }}">
                                <ul class="list-unstyled">
                                    <li>
                                        <b>{{ $user->name }}</b>
                                    </li>
                                    <li>
                                        {{ '@'.$user->username }}
                                    </li>
                                </ul>
                            </a>
                        </div>
                        <div class="col-lg-12">
                            <ul class="list-unstyled list-inline">
                                <li>
                                    <a href="/{{ $user->username }}">
                                        <ul class="list-unstyled">
                                            <li>Tweets</li>
                                            <li>{{ $tweet_count }}</li>
                                        </ul>
                                    </a>
                                </li>
                                <li>
                                    <a href="/{{ $user->username }}/followers">
                                        <ul class="list-unstyled">
                                            <li>Followers</li>
                                            <li>{{ $follower_count }}</li>
                                        </ul>
                                    </a>
                                </li>
                                <li>
                                    <a href="/{{ $user->username }}/following">
                                        <ul class="list-unstyled">
                                            <li>Following</li>
                                            <li>{{ $following_count }}</li>
                                        </ul>
                                    </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                @include('partials.tweet_form')

                <div id="feed">

                </div>

        <div class="spinner" id="loading">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
        <div class="stream-end">
            <i class="icofont icofont-animal-woodpecker"></i>
            <p><button class="btn btn-default btn-sm" onclick="backtotop()" id="topbtn">Back to top ↑</button></p>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
