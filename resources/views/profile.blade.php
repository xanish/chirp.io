{{$showFollows = 'false'}}
{{$follower_count = '10'}}
{{$following_count = '14'}}
{{$user = Auth::user()}}
{{$append = 'true'}}
@extends('layouts.profile')

@section('content')
<script>
         function getTweet(){
            $.ajax({
               type:'POST',
               url:'/tweet',
               data:'tweet_text',
               success:function(data){
                  alert("Your tweet has been posted");
               }
            });
         }
</script>

<div class="profile-feed container">
  @if (Auth::user())
  <div class="row">
    <form class="form">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening !"></textarea>
      <br>
      <button onclick="getTweet()" class="btn btn-default" id="tweet-button" type="submit" style="float: right;"><i class="icofont icofont-animal-woodpecker"></i> Chirp it</button>
    </form>
  </div>
  <br>
  @endif
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
