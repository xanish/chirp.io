@extends('layouts.profile')

@section('content')
<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">

    <h3>Your tweets</h3>
    @if ($user->username == Auth::user()->username)
    {!! Form::open(['method' => 'POST', 'url' => 'tweet']) !!}
        <div class="form-group hidden-xs">
            <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening!"></textarea>
        </div>
        <div class="form-group hidden-xs">
            <button onclick="getTweet()" class="btn btn-primary" id="tweet-button" type="submit"><i class="icofont icofont-animal-woodpecker"></i> Chirp</button>
        </div>
    {!! Form::close() !!}
    @endif
    @foreach ($tweets as $tweet)
    <div class="row padding-20-top-bottom">
        <div class="col-lg-1 col-sm-1 col-xs-2">
            <img class="img-circle img-responsive" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
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
              {{ $tweet->text }}
            </div>
        </div>
    </div>
    @endforeach

</div>
@endsection
