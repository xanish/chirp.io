@extends('layouts.app')

@section('content')
    <div class="jumbotron default-color">
        <div class="text-center white-text">
            <h1 class="pacifico">Welcome to Chirp.io</h1>
            <div class="row">
                <form action="">
                    <div class="form-group col-lg-6 col-lg-push-3 col-md-4 col-md-push-4 col-sm-6 col-sm-push-3 col-xs-12 margin-top-10">
                        <input class="main-page-search-field" placeholder="Search what's happening around" type="text" name="search" id="search">
                        <div class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-10 col-sm-push-1 col-xs-12" id="main-results-dropdown">

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="text-center">
            <!-- <h1 class="pacifico grey-text">Coming Soon</h1> -->
        </div>
    </div>
@endsection
