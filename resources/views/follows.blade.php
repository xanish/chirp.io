@extends('layouts.profile')
@section('content')
<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
  <h3>{{ $header }}</h3>
  @foreach ($data as $person)
  <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6">
    <div class="row card">
      <div class="profile-img col-lg-2 col-md-2 col-sm-12 col-xs-12">
        <img class="circle-img img-responsive" src="{{ asset(Config::get('constants.avatars').$person->profile_image) }}" alt="">
      </div>
      <div class="personal-details col-lg-7 col-md-7 col-sm-12 col-xs-12 hidden-sm hidden-xs">
        <ul class="list-unstyled">
          <li><a href="/{{ $person->username }}"><h4 class="no-bottom-margin">{{ $person->name }}</h4></a></li>
          <li>{{ '@'.$person->username }}</li>
        </ul>
      </div>
      <div class="personal-details col-lg-7 col-md-7 col-sm-12 col-xs-12 visible-sm visible-xs text-center">
        <ul class="list-unstyled">
          <li><a href="/{{ $person->username }}"><h4 class="no-bottom-margin">{{ $person->name }}</h4></a></li>
          <li>{{ '@'.$person->username }}</li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-center">
        @if ($header == 'Following' and $user->username == Auth::user()->username)
        {!! Form::open(['method' => 'PATCH', 'url' => '/following/'.$person->username]) !!}
        {!! Form::hidden('following', $user->username) !!}
        <button type="submit" class="btn btn-danger btn-rounded" onclick="this.disabled=true;this.innerHTML='Unfollowing'; this.form.submit();">Unfollow</button>
        {!! Form::close() !!}
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
