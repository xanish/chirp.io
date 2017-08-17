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
              {!! nl2br($tweet->text) !!}
            </div>
        </div>
    </div>
    @endforeach
