@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="title m-b-md">
            AGM Payments
        </div>
        <h3><a href="{{route('cas.login')}}" class="btn btn-primary">Galaxy Login</a></h3>
        <h6>By logging in, I accept the <a href="{{route('terms')}}" target="_blank"><b>terms of use</b></a></h6>

    </div>
@endsection

