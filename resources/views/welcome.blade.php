@extends('layouts.app')

@section('content')
    <div class="jumbotron default-color">
        <div class="text-center white-text">
            <h1 class="pacifico">Welcome to Chirp.io</h1>
            <div class="row">
                <form action="">
                    <div class="form-group col-lg-6 col-lg-push-3 col-md-4 col-md-push-4 col-sm-6 col-sm-push-3 col-xs-12 margin-top-10">
                        <input id="main-page-search-field" placeholder="Search what's happening around" type="text" name="search" id="search">
                        <div class="text-left col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-10 col-sm-push-1 col-xs-10 col-xs-push-1" id="main-results-dropdown">
                            <ul class="list-unstyled" id="search-results">

                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <h4 class="pacifico">Latest Tweets</h4>
                    @foreach($tweets as $tweet)
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                    <img class="img-responsive img-circle"
                                         src="{{ asset(Config::get('constants.avatars').$tweet->user->profile_image) }}" alt="">
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
                                    <ul class="list-unstyled list-inline">
                                        <li><a href="/{{ $tweet->user->username }}"><h6>{{ $tweet->user->name }}</h6></a></li>
                                        <li><a href="/{{ $tweet->user->username }}">{{ '@'.$tweet->user->username }}</a></li>
                                        <li>{{ $tweet->created_at->toDayDateTimeString() }}</li>
                                    </ul>
                                    <p>
                                        {!! $tweet->text !!}
                                    </p>
                                    @if($tweet->tweet_image != null)
                                        <img src="{{ asset(Config::get('constants.tweet_images').$tweet->tweet_image) }}" class="img-responsive hidden-xs" alt="">
                                    @endif
                                </div>
                                @if($tweet->tweet_image != null)
                                    <div class="col-xs-12 visible-xs">
                                        <img src="{{ asset(Config::get('constants.tweet_images').$tweet->tweet_image) }}" class="img-responsive" alt="">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-action">
                            <h6><a class="red-text"><i class="material-icons">favorite</i> <span>{{ $tweet->likes }}</span></a></h6>
                        </div>
                    </div>
                    <div class="margin-top-10"></div>
                @endforeach
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <h4 class="pacifico">Popular Tags</h4>
                <table class="table">
                    @foreach($popular_tags as $tag)
                        <tr><td><a href="/tag/{{ $tag->tag }}">{{ $tag->tag }}</a></td><td>{{ $tag->tag_count }}</td></tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
