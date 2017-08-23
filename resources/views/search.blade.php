@extends('layouts.app')

@section('content')
<div class="container">
  <h3>Search Results</h3>
  <div class="row">
    @foreach ($data as $result)
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
      <div class="row card">
        <div class="image col-lg-4 col-md-4 col-sm-4 col-xs-4">
          <img src="{{ asset(Config::get('constants.avatars').$result->profile_image) }}" class="img-responsive img-circle" alt="">
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
          <ul class="list-unstyled">
            <li><a href="/{{ $result->username }}"><b>{{ $result->name }}</b></a></li>
            <li>{{ '@'.$result->username }}</li>
            <li>
              @if (!Auth::guest())
              {!! Form::open(['method' => 'POST', 'url' => '/following']) !!}
              {!! Form::hidden('following', $result->username) !!}
              <button type="submit" class="btn btn-primary btn-rounded" onclick="this.disabled=true;this.innerHTML='Following..'; this.form.submit();">Follow</button>
              {!! Form::close() !!}
              @endif
            </li>
          </ul>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
