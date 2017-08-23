@extends('layouts.app')
@section('content')
<script type="text/javascript">
  var profile_image = "{{ asset(Config::get('constants.avatars').$user->profile_image) }}"
</script>
<div class="container">
  <h1>Edit Profile</h1>
  <div class="row">
    {!! Form::model($user, ['method' => 'POST', 'url' => 'edit-profile', 'files' => true, 'id' => 'edit-profile']) !!}
    <div class="col-lg-4 col-sm-12">
      <!-- <img class="img-responsive center-block" src="{{ asset(Config::get('constants.avatars').$user->profile_image) }}" alt=""> -->
      <div class="imageBox center-block">
        <div class="thumbBox"></div>
        <div class="spinner" style="display: none">Loading...</div>
      </div>
      <div class="text-center">
        <div class="form-group">
          <div class="btn-group">
            <input type="button" id="btnCrop" value="Crop" class="btn btn-default">
            <input type="button" id="btnZoomIn" value="+" class="btn btn-default">
            <input type="button" id="btnZoomOut" value="-" class="btn btn-default">
          </div>
        </div>
        <h3>{{ $user->name }}</h3>
        <h4>{{ '@'.$user->username}}</h4>
      </div>
      <div class="form-group">
        <!-- {!! Form::label('profile_image', 'Avatar') !!}
        {!! Form::file('profile_image',['class' => 'form-control', 'accept' => '.jpeg,.png,.jpg,.gif']) !!} -->
        <div class="action">
          {!! Form::file('profile_image',['class' => 'form-control', 'accept' => '.jpeg,.png,.jpg,.gif', 'id' => 'file']) !!}
        </div>
      </div>
      <div class="cropped center-block">

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
