@extends('layouts.app')

@section('content')
<script>
var _tag = {!! json_encode(ltrim($tag, '#')) !!}
</script>
    <div class="container">
        @if($page == 'tags')
            <h4 class="margin-top-90">Tags Containing: {{ $tag }}</h4>
            <div class="row margin-top-50">
                @foreach($tags as $tag)
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 padding-bottom-15">
                        <h6><a href="/tag/{{$tag->tag}}/tweets">#<span class="underline">{{$tag->tag}}</span></a></h6>
                    </div>
                @endforeach
            </div>
            <div class="text-center">
                {{ $tags->links() }}
            </div>
        @else
            <h4 class="margin-top-90">Tweets Containing: {{ $tag }}</h4>
            <div class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-10 col-sm-push-1 col-xs-12">

                <div id="searchfeed">

                </div>

                <div class="spinner" id="loading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>

                <div id="notweetmessage">
                    <h5 class="text-center pacifico grey-text">No tweets containing <span id="tagname"></span> found</h5>
                </div>
            </div>
        @endif

        @include('partials.backtotop')
    </div>

    @if(!Auth::guest())
        @include('partials.tweetmodal')
    @endif
@endsection
