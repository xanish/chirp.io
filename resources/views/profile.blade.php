@extends('layouts.profile')

@section('content')
<script type="text/javascript">
    var tweetcount = {{ json_encode($tweet_count) }};
</script>

<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
@if (!Auth::guest())
    @if ($user->username === Auth::user()->username)
    <div id="tweetform" class="hidden-xs">
        <form id="form">
            <div class="form-group no-margins">
                <textarea class="form-control" name="tweet_text" id="tweetbox" rows="4" placeholder="What's happening!" maxlength="150" wrap="soft" required></textarea>
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
                <div class="col-lg-8 col-md-8 col-sm-8 pad-5">
                    Characters remaining: <span id="count_message"></span>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-no-pad">
                    <button onclick="" type="submit" class="btn btn-primary button-panel pull-right" id="tweet-button" type="button"><i class="icofont icofont-animal-woodpecker"></i> Chirp</button>
                </div>
            </div>
        </form>
    </div>
    <div id="ERRORMSG" class="text-center text-danger"></div>
    @endif
@endif

    <h3>Your tweets</h3>

  <div id="feed-tweet">
      <div class="" id="feed">
          @foreach ($tweets as $tweet)
            @if ($tweet->tweet_image != null)
            <div class="row padding-20-top-bottom">
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
                    <img class="img-circle img-responsive profile-pic" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
                </div>
                <div class="col-lg-11 col-sm-11 col-md-11 col-sm-11 col-xs-9">
                    <b>{{ $user->name }}</b>&nbsp;{{ '@'. $user->username }}&nbsp; - &nbsp;<span class="grey-text">{{ $tweet->created_at->diffForHumans() }}</span>
                    <div class="text">
                      {{ $tweet->text }}
                    </div>
                    <div class="image padding-20 hidden-xs">
                        <img class="img-responsive" src="{{ asset('tweet_images/'.$tweet->tweet_image) }}" alt="">
                    </div>
                </div>
                <div class="image col-xs-12 visible-xs">
                    <img class="img-responsive" src="{{ asset('tweet_images/'.$tweet->tweet_image) }}" alt="">
                </div>
            </div>
            @else
            <div class="row padding-20-top-bottom">
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
                    <img class="img-circle img-responsive profile-pic" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
                </div>
                <div class="col-lg-11 col-sm-11 col-md-11 col-sm-11 col-xs-9">
                    <b>{{ $user->name }}</b>&nbsp;{{ '@'. $user->username }}&nbsp; - &nbsp;<span class="grey-text">{{ $tweet->created_at->diffForHumans() }}</span>
                    <div class="text">
                      {{ $tweet->text }}
                    </div>
                </div>
            </div>
            @endif
          @endforeach

          {{ $tweets->links() }}

      </div>
  </div>
</div>
@endsection
