@extends('layouts.profile')

@section('content')
<h1 class="header">{{ $header }}</h1>

@foreach ($data as $person)
<div class="col-lg-6 col-md-6 col-sm-12">
    <div class="row card">
        <div class="profile-img col-lg-2">
            <img class="circle-img" src="http://via.placeholder.com/60x60/6255b2/ffffff" alt="">
        </div>
        <div class="personal-details col-lg-8">
            <a href="/{{ $person->username }}"><h4>{{ $person->name }}</h4></a>
            <h5>{{ '@'.$person->username }}</h5>
        </div>
        <div class="col-lg-2">
            @if ($header == 'Following')
            {!! Form::open(['method' => 'PATCH', 'url' => '/following/'.$person->username]) !!}
            {!! Form::hidden('following', $user->username) !!}
            <button type="submit" class="btn btn-danger btn-rounded" onclick="this.disabled=true;this.innerHTML='Unfollowing..'; this.form.submit();">Unfollow</button>
            {!! Form::close() !!}
            @endif
        </div>
    </div>
</div>

@endforeach
@endsection
