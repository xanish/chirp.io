@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-20-top-bottom">
            <div class="details">
                <div class="photo col-lg-3 col-sm-3 col-xs-3 col-no-pad">
                    <img class="img-responsive" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
                </div>
                <div class="name col-lg-9 col-sm-9 col-xs-9">
                    <ul class="list-unstyled">
                        <a href="/{{ $user->username }}"><li>{{ $user->name }}</li></a>
                        <a href="/{{ $user->username }}"><li>{{ '@'.$user->username }}</li></a>
                    </ul>
                </div>
                <div class="details col-lg-12 col-sm-12 col-xs-9 col-no-pad">
                    <ul class="list-inline">
                        <li>
                            <ul class="list-unstyled">
                                <a href="/{{ $user->username }}" class="nounderline">
                                    <li>Tweets</li>
                                    <li>{{ $tweet_count }}</li>
                                </a>
                            </ul>
                        </li>
                        <li>
                            <ul class="list-unstyled">
                                <a href="/followers/{{ $user->username }}" class="nounderline">
                                    <li>Followers</li>
                                    <li>{{ $follower_count }}</li>
                                </a>
                            </ul>
                        </li>
                        <li>
                            <ul class="list-unstyled">
                                <a href="/following/{{ $user->username }}" class="nounderline">
                                    <li>Following</li>
                                    <li>{{ $following_count }}</li>
                                </a>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-9 col-xs-12">
            <div class="row padding-20 hidden-xs">
                {!! Form::open(['method' => 'POST', 'url' => 'tweet']) !!}
                    <div class="form-group">
                        <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening!"></textarea>
                    </div>
                    <div class="form-group">
                        <button onclick="getTweet()" class="btn btn-primary" id="tweet-button" type="submit"><i class="icofont icofont-animal-woodpecker"></i> Chirp</button>
                    </div>
                {!! Form::close() !!}
            </div>

            @foreach ($feed as $post)
            <div class="row padding-20-top-bottom">
                <div class="col-lg-1 col-sm-1 col-xs-2">
                    <img class="img-circle img-responsive" src="{{ asset('avatars/'.$post->profile_image) }}" alt="">
                </div>
                <div class="col-lg-11 col-sm-11 col-xs-10">
                    <div class="row">
                        <div class="col-lg-8 col-xs-8">
                            <a href="/{{ $post->username }}"><b>{{ $post->name }}</b>&nbsp;{{ '@'. $post->username }}</a>
                        </div>
                        <div class="col-lg-4 col-xs-4 text-right grey-text">
                            {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="">
                      {{ $post->text }}
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
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
@endsection
