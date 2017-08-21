@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 padding-20-top-bottom">
            <div class="details">
                <div class="photo col-lg-3 col-md-3 col-sm-2 col-xs-3 col-no-pad">
                    <img class="img-responsive" src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}" alt="">
                </div>
                <div class="name col-lg-9 col-md-9 col-sm-9 col-xs-9">
                    <ul class="list-unstyled">
                        <a href="/{{ $user->username }}"><li>{{ $user->name }}</li></a>
                        <a href="/{{ $user->username }}"><li>{{ '@'.$user->username }}</li></a>
                    </ul>
                </div>
                <div class="details col-lg-12 col-md-12 col-sm-9 col-xs-12 col-no-pad">
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
        <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="row padding-20 hidden-xs">
                <div id="tweetform">
                    <form id="form">
                        <div class="form-group no-margins">
                            <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening!" maxlength="150" wrap="soft"></textarea>
                        </div>
                        <div class="form-group row row-no-neg-margin no-margins">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-no-pad">
                                <input type="file" id="tweet_image_file" name="tweet_image" style="display: none;" accept=".jpeg,.png,.jpg,.gif">
                                <input type="file" id="tweet_video_file" name="tweet_video" style="display: none;" accept=".mp4">
                                <div class="input-group">
                                    <button type="button" class="btn button-panel" onclick="document.getElementById('tweet_image_file').click();"><span class="fa fa-image"></span></button>
                                    <!-- <button type="button" class="btn button-panel" onclick="document.getElementById('tweet_video_file').click();"><span class="fa fa-video-camera"></span></button> -->
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 pad-5" id="success-msg">

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 pad-5 text-right">
                                Characters remaining: <span id="count_message"></span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-no-pad">
                                <button onclick="" type="submit" class="btn btn-primary button-panel pull-right" id="tweet-button" type="button"><i class="icofont icofont-animal-woodpecker"></i> Chirp</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @foreach ($feed as $post)
            @if ($post->tweet_image != null)
            <div class="row padding-20-top-bottom">
                <div class="col-lg-1 col-sm-1 col-xs-2">
                    <img class="img-circle img-responsive" src="{{ asset(Config::get('constants.avatars').$post->profile_image) }}" alt="">
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
                    <div class="image padding-20 hidden-xs">
                        <img class="img-responsive" src="{{ asset(Config::get('constants.tweet_images').$post->tweet_image) }}" alt="">
                    </div>
                </div>
                <div class="image col-xs-12 visible-xs">
                    <img class="img-responsive" src="{{ asset(Config::get('constants.tweet_images').$post->tweet_image) }}" alt="">
                </div>
            </div>
            @else
            <div class="row padding-20-top-bottom">
                <div class="col-lg-1 col-sm-1 col-xs-2">
                    <img class="img-circle img-responsive" src="{{ asset(Config::get('constants.avatars').$post->profile_image) }}" alt="">
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
            @endif
            @endforeach

        </div>
    </div>
</div>
<div class="footer footer-fixed visible-xs">
    {!! Form::open(['method' => 'POST', 'url' => 'tweet', 'id' => 'mobile-form']) !!}
        <div class="input-group">
          <input type="text" class="form-control styled-input" placeholder="What's happening!" name="tweet_text" id="tweettext">
          <span class="input-group-btn">
            <button class="btn btn-primary styled-input" type="submit" id="tweet-button" type="submit"><i class="icofont icofont-animal-woodpecker"></i></button>
          </span>
        </div>
    {!! Form::close() !!}
</div>
@endsection
