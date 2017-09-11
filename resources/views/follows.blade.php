@extends('partials.profile')

@section('data')
    <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12">
        <div class="row">
            @if(count($people) == 0)
                @if($path == $user->username.'/following')
                    <h5 class="pacifico text-center">You don't seem to be following anyone <i class="material-icons">sentiment_very_dissatisfied</i></h5>
                @else
                    <h5 class="pacifico text-center">You don't have any followers <i class="material-icons">sentiment_very_dissatisfied</i></h5>
                @endif
            @else
                @foreach($people as $person)
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                        <img class="img-responsive img-circle"
                                             src="{{ asset(Config::get('constants.avatars').$person->profile_image) }}" alt="">
                                    </div>
                                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                        <a href="/{{ $person->username }}">
                                          <ul class="list-unstyled">
                                              <li><h6>{{ $person->name }}</h6></li>
                                              <li>{{ '@'.$person->username }}</li>
                                          </ul>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if($user->username == Auth::user()->username)
                                @if($path == $user->username.'/following' and Auth::user()->username == $user->username)
                                <div class="card-action">
                                    <button type="button" id="{{ $person->id }}" class="btn btn-danger btn-block unfollow">Unfollow</button>
                                </div>
                                @else
                                <div class="card-action">
                                    <button type="button" id="{{ $person->id }}" class="btn btn-default btn-block follow">Follow</button>
                                </div>
                                @endif
                            @endif
                        </div>
                        <div class="margin-top-10"></div>
                    </div>
                @endforeach
                {{ $people->links() }}
            @endif
        </div>
    </div>
@endsection
