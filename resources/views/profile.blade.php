@extends('layouts.profile')

@section('content')
<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">

<h3>Your tweets</h3>
@if ($user->username == Auth::user()->username)
<div id="tweetform">
    <div class="form-group">
        <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening !" maxlength="150"></textarea>
    </div>
    <div class="form-group">
        <label onclick="getTweet()" class="btn btn-primary" id="tweet-button" type="button" disabled="disabled" style="float: left;"><i class="icofont icofont-animal-woodpecker"></i> Chirp</label>
        <h5 class="col-sm-1" id="count_message"></h5>
    </div>
    <br><br><br>
</div>
@endif

  <div class="container" id="feed-tweet">
    @foreach ($tweets as $tweet)
    <div class="row padding-20-top-bottom">
        <div class="col-sm-1">
            <img class="img-circle img-responsive profile-pic" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
        </div>
        <div class="col-lg-11 col-sm-11 col-xs-10">
            <div class="row">
                <div class="col-lg-8 col-xs-8">
                    <b>{{ $user->name }}</b>&nbsp;{{ '@'. $user->username }}
                </div>
                <div class="col-lg-4 col-xs-4 text-right grey-text">
                    {{ $tweet->created_at->diffForHumans() }}
                </div>
            </div>
            <div class="">
              {!! nl2br($tweet->text) !!}
            </div>
        </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
