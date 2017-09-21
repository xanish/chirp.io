@extends('layouts.app')

@section('content')
<div class="jumbotron default-color">
    <div class="text-center white-text">
        <h1 class="pacifico">Welcome to Chirp.io</h1>
        <div class="row no-margin">
            <form action="">
                <div class="form-group col-lg-6 col-lg-push-3 col-md-4 col-md-push-4 col-sm-6 col-sm-push-3 col-xs-12 margin-top-10">
                    <input id="main-page-search-field" placeholder="Search what's happening around" type="text" name="search" id="search">
                    <div class="text-left col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-10 col-sm-push-1 col-xs-10 col-xs-push-1" id="main-results-dropdown" style="display:none">
                        <ul class="list-unstyled" id="search-results">

                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container" onload="load_popular_tags()">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <h4 class="pacifico">Latest Tweets</h4>
            <div id="welcomefeed">
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <h4 class="pacifico">Popular Tags</h4>
            <table class="table" id="popular-tags">
                <div class="spinner" id="loading">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </table>
        </div>
    </div>
</div>
@endsection
