@extends('partials.profile')

@section('data')
    <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12">
        <div class="row">
            @foreach($people as $person)
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                    <img class="img-responsive img-circle"
                                         src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}" alt="">
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
                        @if($path == $user->username.'/following')
                        <div class="card-action">
                            <form method="POST" action="{{ '/unfollow/'.$person->username }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" id="unfollowbtn" class="btn btn-danger">Unfollow</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    <div class="margin-top-10"></div>
                </div>

            @endforeach
        </div>

    </div>
@endsection
