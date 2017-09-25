@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row margin-top-90">
            {!! Form::model($user, ['method' => 'PATCH', 'url' => 'edit-profile', 'files' => true, 'id' => 'edit-profile']) !!}
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <img class="img-responsive center-block" src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}" alt="">
                <div class="text-center">
                    <h5>{{ $user->name }}</h5>
                    <h6>{{ '@'.$user->username}}</h6>
                    <h6>{{ $user->email }}</h6>
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
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <div class="form-group">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'edit-name']) !!}
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
                    <input type="text" data-provide="datepicker" value="{{ $user->birthdate }}" class="form-control" id="edit-bday" name="birthdate" data-date-format="yyyy-mm-dd" data-date-end-date="0d">
                </div>
                <div class="form-group">
                    {!! Form::label('default', 'Theme Color') !!}
                    <ul class="list-unstyled list-inline">
                        @foreach($colors as $c)
                            <li><input type="radio" class="option-input radio {{ $c }}" name="color" value="{{ $c }}" id="{{ $c }}"/></li>
                        @endforeach
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
            <ul class="alert alert-success" id="success-update-msg">
                <li>{{ $success }}</li>
            </ul>
        @endif
    </div>

    @include('partials.tweetmodal')
@endsection
