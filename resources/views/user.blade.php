@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h2>About me</h2>
                        <div class="row">
                            <div class="pull-left ">
                                <img src="{{ Auth::user()->picture }}" class="col-lg-3" class="img-responsive" alt="Responsive image">
                                <h4 class="col-md-9">{{ Auth::user()->name }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
