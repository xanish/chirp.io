@extends('layouts.profile')

@section('content')

@if ($user->username == Auth::user()->username)
    <div class="form-group">
        <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening !"></textarea>
    </div>
    <div class="form-group">
        <label onclick="getTweet()" class="btn btn-primary" id="tweet-button" type="button" disabled="disabled"><i class="icofont icofont-animal-woodpecker"></i> Chirp</label>
    </div>
@endif

<div class="container" id="feed-tweet">
    @foreach ($tweets as $tweet)
    <div class="row padding-20-top-bottom">
        <div class="col-lg-1">
            <img class="img-circle img-responsive" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
        </div>
        <div class="col-lg-11">
            <div class="row">
                <div class="col-lg-8">
                    <b>{{ $user->name }}</b>&nbsp;{{ '@'. $user->username }}
                </div>
                <div class="col-lg-4 text-right grey-text">
                    {{ $tweet->created_at->diffForHumans() }}
                </div>
            </div>
            <div class="">
              {{ $tweet->text }}
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
