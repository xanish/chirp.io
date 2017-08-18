@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>
    <div class="row">
        {!! Form::model($user, ['method' => 'PATCH', 'url' => 'edit-profile', 'files' => true]) !!}
        <div class="col-lg-3 col-sm-12">
            <img class="img-responsive center-block" src="{{ asset('avatars/'.$user->profile_image) }}" alt="">
            <div class="text-center">
                <h3>{{ $user->name }}</h3>
                <h4>{{ '@'.$user->username}}</h4>
            </div>
            <div class="form-group">
              {!! Form::label('profile_image', 'Avatar') !!}
              {!! Form::file('profile_image',['class' => 'form-control', 'accept' => '.jpeg,.png,.jpg,.gif']) !!}
            </div>

        </div>

        <div class="col-lg-9 col-sm-12">

            <div class="form-group">
              {!! Form::label('name', 'Name') !!}
              {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
              {!! Form::label('email', 'Email') !!}
              {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
              {!! Form::label('city', 'City') !!}
              {!! Form::text('city', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
              {!! Form::label('country', 'Country') !!}
              {!! Form::text('country', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
              {!! Form::label('birthdate', 'Birthdate') !!}
              {!! Form::input('date', 'birthdate', $user->birthdate, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
              {!! Form::submit('Update Profile', ['class' => 'btn btn-primary form-control']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    @if ($errors->any())
    <ul class="alert alert-danger">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    @endif
</div>
@endsection
