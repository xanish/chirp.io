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
