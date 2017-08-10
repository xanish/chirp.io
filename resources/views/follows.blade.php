@extends('layouts.profile')

@section('content')
<h1 class="header">{{ $header }}</h1>

@foreach ($data as $person)

<div class="col-lg-6">
    <div class="row card">
        <div class="profile-img col-lg-2">
            <img class="circle-img" src="http://via.placeholder.com/60x60/6255b2/ffffff" alt="">
        </div>
        <div class="personal-details col-lg-9">
            <a href="/{{ $person->username }}"><h4>{{ $person->name }}</h4></a>
            <h5>{{ '@'.$person->username }}</h5>
        </div>
        <div class="col-lg-1">
            <a href="#"><span class="fa fa-remove"></span></a>
        </div>
    </div>
</div>

@endforeach

@endsection
