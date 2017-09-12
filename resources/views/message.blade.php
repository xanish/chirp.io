@extends('layouts.auth')
@section('content')
<div class="container">
    <div class="row margin-top-90">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h5>{{ $title }}</h5></div>
                <div class="panel-body">
                    {!! $msg !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
