@extends('layouts.app')

@section('content')
<div class="container">
  <h4>Users matching: {{ $criteria }}</h4>
    @if(count($data) == 0)
        <h5 class="text-center pacifico grey-text">No users found</h5>
    @else
        @foreach ($data as $result)
            @if(Auth::guest() or $result->username != Auth::user()->username)
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
                            <button type="button" id="{{ $result->id }}" class="btn btn-danger btn-block unfollow">Unfollow</button>
                            @else
                            <button type="button" id="{{ $result->id }}" class="btn btn-default btn-block follow">Follow</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
  </div>
  @if(count($data) > 0)
    <div class="text-center">
        {{ $data->links() }}
    </div>
  @endif
  
  @include('partials.tweetmodal')
@endsection
