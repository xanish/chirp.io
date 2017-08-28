@extends('layouts.app')

@section('content')
<div class="container">
  <h4>Search Results</h4>
    @foreach ($data as $result)
        @if($result->username != Auth::user()->username)
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-top-10">
                <div class="card">
                    <div class="card-image">
                        <div class="view overlay hm-white-slight z-depth-1">
                            <img src="{{ asset(Config::get('constants.banners').$result->profile_banner) }}"
                                 class="img-responsive" alt="">
                            <a href="#">
                                <div class="mask waves-effect"></div>
                            </a>
                        </div>
                    </div>
                    <div class="card-content row">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <img src="{{ asset(Config::get('constants.avatars').$result->profile_image) }}"
                                 class="img-responsive img-circle img-floating"></img>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <a href="/{{ $result->username }}">
                                <ul class="list-unstyled">
                                    <li>
                                        <b>{{ $result->name }}</b>
                                    </li>
                                    <li>
                                        {{ '@'.$result->username }}
                                    </li>
                                </ul>
                            </a>
                        </div>
                    </div>
                    <div class="card-action">
                        @if (in_array($result->id, $ids))
                        {!! Form::open(['method' => 'DELETE', 'url' => '/unfollow/'.$result->username]) !!}
                        <button type="submit" class="btn btn-danger btn-block" onclick="this.disabled=true;this.innerHTML='Unfollowing'; this.form.submit();">Unfollow</button>
                        {!! Form::close() !!}
                        @else
                        {!! Form::open(['method' => 'POST', 'url' => '/follow/'.$result->username]) !!}
                        <button type="submit" class="btn btn-default btn-block" onclick="this.disabled=true;this.innerHTML='Following..'; this.form.submit();">Follow</button>
                        {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endforeach
  </div>
@endsection
