@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row margin-top-70">
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 margin-bottom-20">
                <div class="card">
                    <div class="card-image">
                        <div class="view overlay hm-white-slight z-depth-1">
                            <a href="{{ asset(Config::get('constants.banners').'original_'.$user->profile_banner) }}" data-lightbox="box-{{ $user->id }}"><img src="{{ asset(Config::get('constants.banners').$user->profile_banner) }}"
                                 class="img-responsive lightboxed" alt=""></a>
                            <!-- <a href="#">
                                <div class="mask waves-effect"></div>
                            </a> -->
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-3">
                            <img src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}"
                                 class="img-responsive img-circle img-floating"></img>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-9">
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="count-bar">
                            <ul id="navcount" class="list-unstyled list-inline">
                                <li>
                                    <a href="/{{ $user->username }}">
                                        <ul class="list-unstyled text-center">
                                            <li>Tweets</li>
                                            <li>{{ $tweet_count }}</li>
                                        </ul>
                                    </a>
                                </li>
                                <li>
                                    <a href="/{{ $user->username }}/followers">
                                        <ul class="list-unstyled text-center">
                                            <li>Followers</li>
                                            <li>{{ $follower_count }}</li>
                                        </ul>
                                    </a>
                                </li>
                                <li>
                                    <a href="/{{ $user->username }}/following">
                                        <ul class="list-unstyled text-center">
                                            <li>Following</li>
                                            <li>{{ $following_count }}</li>
                                        </ul>
                                    </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                @include('partials.tweet_form')

                <div class="tweet-alert">
                    <div id="card-alert" class="alert alert-primary">
                      <div class="alerttext">
                        <p class="no-margin">View <span id="newcount">0</span> new Tweet</p>
                      </div>
                    </div>
                    <div class="margin-top-10">
                    </div>
                </div>
                <div id="feed">

                </div>

            <div class="spinner" id="loading">
              <div class="rect1"></div>
              <div class="rect2"></div>
              <div class="rect3"></div>
              <div class="rect4"></div>
              <div class="rect5"></div>
            </div>

            <div id="notweetmessage">
              <h5 class="pacifico">No tweets to show</h5>
            </div>

            @include('partials.backtotop')
        </div>
    </div>
  </div>
</div>
@endsection
