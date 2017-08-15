<!--{{$showFollows = 'false'}}
{{$follower_count = '10'}}
{{$following_count = '14'}}
{{$user = Auth::user()}}
{{$append = 'true'}}-->
@extends('layouts.profile')

@section('content')

<div class="profile-feed container-fluid">
  @if (Auth::user())
  <div class="row">

      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening !"></textarea>
      <br>
      <button onclick="getTweet()" class="btn btn-default" id="tweet-button" disabled="disabled" type="submit" style="float: right;"><i class="icofont icofont-animal-woodpecker"></i> Chirp it</button>

  </div>
  <br>
  @endif
  <div class="tweets container" id="feed-tweet">
    @foreach ($feeds as $feed)
    <div class="row">
        <div class="col-sm-1">
            <img class="img-circle img-responsive" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
        </div>
        <div class="col-lg-10">
            <b>{{ Auth::user()->name }}</b>
            {{'@'. Auth::user()->username}} . {{ $feed->created_at }}
            <div class="">
              {{ $feed->text }}
              <!--<div>
                <br>
                <button type="submit" class="btn">
                  <i class="fa fa-retweet"></i>
                </button>
              </div>-->
            </div>
        </div>
    </div>
    <br><br><br>
    @endforeach
  </div>
</div>

@endsection
