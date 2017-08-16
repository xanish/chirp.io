<!--{{$showFollows = 'false'}}
{{$follower_count = '10'}}
{{$following_count = '14'}}
{{$user = Auth::user()}}
{{$append = 'true'}}-->
@extends('layouts.profile')

@section('content')

@if ($user->username == Auth::user()->username)
{!! Form::open(['method' => 'POST', 'url' => 'tweet']) !!}
    <div class="form-group">
        <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening !"></textarea>
    </div>
    <div class="form-group">
        <button onclick="getTweet()" class="btn btn-primary" id="tweet-button" type="submit"><i class="icofont icofont-animal-woodpecker"></i> Chirp</button>
    </div>
{!! Form::close() !!}
@endif

<div class="container">
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
