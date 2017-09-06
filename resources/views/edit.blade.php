@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {!! Form::model($user, ['method' => 'PATCH', 'url' => 'edit-profile', 'files' => true, 'id' => 'edit-profile']) !!}
            <div class="col-lg-4 col-sm-12">
            <img class="img-responsive center-block" src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}" alt="">
                <div class="text-center">
                    <h5>{{ $user->name }}</h5>
                    <h6>{{ '@'.$user->username}}</h6>
                </div>
                <div class="form-group">
                {!! Form::label('profile_image', 'Avatar') !!}
                {!! Form::file('profile_image',['class' => 'form-control', 'accept' => '.jpeg,.png,.jpg,.gif']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('profile_banner', 'Banner') !!}
                    {!! Form::file('profile_banner',['class' => 'form-control', 'accept' => '.jpeg,.png,.jpg,.gif']) !!}
                </div>
            </div>
            <div class="col-lg-8 col-sm-12">
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'edit-name']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::text('email', null, ['class' => 'form-control', 'id' => 'edit-email']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('city', 'City') !!}
                    {!! Form::text('city', null, ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'city']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('country', 'Country') !!}
                    {!! Form::text('country', null, ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'country']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('birthdate', 'Birthdate') !!}
                    {!! Form::input('date', 'birthdate', $user->birthdate, ['class' => 'form-control', 'id' => 'edit-bday']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('default', 'Theme Color') !!}
                    <ul class="list-unstyled list-inline">
                        <li><input type="radio" class="option-input radio stock" name="color" value="default"/></li>
                        <li><input type="radio" class="option-input radio blue" name="color" value="blue" /></li>
                        <li><input type="radio" class="option-input radio deep-purple" name="color" value="deep-purple" /></li>
                        <li><input type="radio" class="option-input radio pink" name="color" value="pink" /></li>
                        <li><input type="radio" class="option-input radio green" name="color" value="green" /></li>
                        <li><input type="radio" class="option-input radio orange" name="color" value="orange" /></li>
                    </ul>
                </div>
                <div class="form-group margin-top-10">
                    {!! Form::submit('Update Profile', ['class' => 'btn btn-default btn-block form-control']) !!}
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
        @if ($success)
            <ul class="alert alert-success">
                <li>{{ $success }}</li>
            </ul>
        @endif
    </div>
@endsection
