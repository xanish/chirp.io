@extends('layouts.profile')

@section('content')
<div class="profile-feed container">
  <div class="row">
    <form class="form">
      <textarea class="form-control" name="tweet-text" id="tweetbox" rows="4" placeholder="What's happening !"></textarea>
      <br>
      <button class="btn btn-default" id="tweet-button" type="submit" style="float: right;"><i class="icofont icofont-animal-woodpecker"></i> Chirp it</button>
    </form>
  </div>
  <br>
  <div class="tweets container" id="feed-tweet">
    @foreach ($feeds as $feed)
    <div class="row">
        <div class="col-sm-1">
            <img class="img-circle" src="http://via.placeholder.com/60x60/6255b2/ffffff" alt="">
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
    <br><br>
    @endforeach
  </div>
</div>

@endsection
