@extends('layouts.app')

@section('content')
    <div class="jumbotron default-color">
        <div class="text-center white-text">
            <h1 class="pacifico">Welcome to Chirp.io</h1>
            <div class="row">
                <form action="">
                    <div class="form-group col-lg-4 col-lg-push-4 col-md-4 col-md-push-4 col-sm-6 col-sm-push-3 col-xs-12 margin-top-10">
                        <input class="main-page-search-field" placeholder="Search what's happening around" type="text" name="search" id="search">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="text-center">
            <h1>WIP! Popular Stuff</h1>
        </div>
    </div>
@endsection