@extends('partials.profile')

@section('data')
    <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12" >
        @if(!Auth::guest())
            @if(Auth::user()->username == $user->username)
                @include('partials.tweet_form')
            @else
                @include('partials.tweetmodal')
            @endif
        @endif
        <div id="feed-tweet">

        </div>
        <div class="spinner" id="loading">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>

        <div id="notweetmessageprofile">
          <h5 class="pacifico">You haven't tweeted anything yet</h5>
        </div>

        <div class="stream-end">
            <i class="icofont icofont-animal-woodpecker"></i>
            <p><button class="btn btn-default btn-sm" onclick="backtotop()" id="topbtn">Back to top ↑</button></p>
        </div>
    </div>
@endsection
