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

                @foreach($posts as $post)
                    <div class="card margin-top-10">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                    <img class="img-responsive img-circle"
                                         src="{{ asset(Config::get('constants.avatars').$post->profile_image) }}" alt="">
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
                                    <ul class="list-unstyled list-inline">
                                        <li><h6>{{ $post->name }}</h6></li>
                                        <li>{{ '@'.$post->username }}</li>
                                        <li>{{ $post->created_at->diffForHumans() }}</li>
                                    </ul>
                                    <p>
                                        {{ $post->text }}
                                    </p>
                                    @if($post->tweet_image != null)
                                        <img src="{{ asset(Config::get('constants.tweet_images').$post->tweet_image) }}" class="img-responsive hidden-xs" alt="">
                                    @endif
                                </div>
                                @if($post->tweet_image != null)
                                    <div class="col-xs-12 visible-xs">
                                        <img src="{{ asset(Config::get('constants.tweet_images').$post->tweet_image) }}" class="img-responsive" alt="">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-action">
                            <form method="POST" id="like_form_{{$post->id}}" action="{{ '/like/'.$post->id }}">
                                {{ csrf_field() }}
                                <h6><a class="red-text" onclick="document.getElementById('like_form_{{ $post->id }}').submit();"><i class="material-icons red-text">favorite_border</i> <span>{{$post->likes}}</span></a></h6>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
