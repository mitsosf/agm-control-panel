@extends('layouts.app')

@section('background')style='background: #2e3192'@endsection

@section('content')
    <div style="text-align: center;margin-top: 2%">
        <h2 style="color: white">Ready to</h2>
        <h2 style="color: white">#sailWithUs?</h2>
        <img src="{{asset('images/logo-white.png')}}" style="width: 30%;height: 30%;">
        <h3 style="margin-top: 5%;margin-bottom: 2%"><a href="{{route('login')}}" class="btn btn-success">Login</a></h3>
        <div style="text-align: center;color: white"><h6>By logging in, I accept the <a href="{{route('terms')}}" target="_blank"><b>terms of use</b></a><br> and the privacy policies <a target="_blank" href="https://esngreece.gr/privacy-policy">[1]</a><a
                        href="https://accounts.esn.org/privacy-policy" target="_blank">[2]</a>.</h6></div>
    </div>


@endsection


