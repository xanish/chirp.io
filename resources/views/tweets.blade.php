@extends('partials.profile')

@section('data')
    <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12" >
        @if(Auth::user()->username == $user->username)
            @include('partials.tweet_form')
        @endif
        <div id="feed-tweet">
            @foreach($tweets as $tweet)
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                <img class="img-responsive img-circle"
                                     src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}" alt="">
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
                                <ul class="list-unstyled list-inline">
                                    <li><h6>{{ $user->name }}</h6></li>
                                    <li>{{ '@'.$user->username }}</li>
                                    <li>{{ $tweet->created_at->diffForHumans() }}</li>
                                </ul>
                                <p>
                                    {{ $tweet->text }}
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
                    @if($user->username == Auth::user()->username)
                        <div class="card-action">
                            <h6><a href=""><i class="material-icons red-text">favorite</i></a></h6>
                        </div>
                    @else
                        <div class="card-action">
                            <form method="POST" id="like_form" action="{{ '/unlike/'.$tweet->id }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <h6><a onclick="document.getElementById('like_form').submit();"><i class="material-icons red-text">favorite_border</i></a></h6>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="margin-top-10"></div>
            @endforeach
        </div>
    </div>
@endsection
