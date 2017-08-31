@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Tweets Containing: {{ $tag }}</h4>
        @foreach($posts as $post)
        <div class="card margin-top-10">
            <div class="card-content">
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
                        <img class="img-responsive img-circle"
                             src="{{ asset(Config::get('constants.avatars').$post->profile_image) }}" alt="">
                    </div>
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10">
                        <ul class="list-unstyled list-inline">
                            <li><h6>{{ $post->name }}</h6></li>
                            <li>{{ '@'.$post->username }}</li>
                            <li>{{ $post->created_at->toDayDateTimeString() }}</li>
                        </ul>
                        <p>
                            @foreach($post->text as $word)
                                @if(in_array($word, $post->tags))
                                    <a href="/tag/{{ ltrim($word, '#') }}">{{ $word }}</a>
                                @else
                                    {{ $word }}
                                @endif
                            @endforeach
                        </p>
                        @if($post->tweet_image != null)
                            <a href="{{ asset(Config::get('constants.tweet_images').$post->original_image) }}" data-lightbox="box-{{ $post->id }}">
                                <img src="{{ asset(Config::get('constants.tweet_images').$post->tweet_image) }}" class="img-responsive hidden-xs lightboxed" alt="">
                            </a>
                        @endif
                    </div>
                    @if($post->tweet_image != null)
                        <div class="col-xs-12 visible-xs">
                            <a href="{{ asset(Config::get('constants.tweet_images').$post->original_image) }}" data-lightbox="box-{{ $post->id }}-mini">
                                <img src="{{ asset(Config::get('constants.tweet_images').$post->tweet_image) }}" class="img-responsive lightboxed" alt="">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @if(!in_array($post->id, $liked))
                <div class="card-action">
                    <form method="POST" id="like_form_{{ $post->id }}" action="{{ '/like/'.$post->id }}">
                        {{ csrf_field() }}
                        <h6><a class="red-text" onclick="document.getElementById('like_form_{{ $post->id }}').submit();"><i class="material-icons">favorite_border</i> <span>{{ $post->likes }}</span></a></h6>
                    </form>
                </div>
            @else
            <div class="card-action">
                <form method="POST" id="unlike_form_{{ $post->id }}" action="{{ '/unlike/'.$post->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <h6><a class="red-text" onclick="document.getElementById('unlike_form_{{ $post->id }}').submit();"><i class="material-icons">favorite</i> <span>{{ $post->likes }}</span></a></h6>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
@endsection
