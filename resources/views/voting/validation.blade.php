@extends('layouts.voting.master')

@section('content')
    <div style="text-align: center">
        <h1 style="margin-bottom: 2%">You are about to {!! $user->delegate==1?'<b style="color: green">give</b> a device to':'<b style="color: red">take</b> a device from' !!}:</h1>
        <a href="{{$user->photo}}" target="_blank"><img src="{{$user->photo}}" alt="User photo" width="10%"></a>
        <h1>Name: <u style="color: red">{{$user->name.' '.$user->surname}}</u></h1>
        @if(strpos($user->spot_type, 'National Representative'))
            <h1 style="color: red">NR</h1>
        @endif
        <h2>Section: <b style="color: red">{{$user->section}}</b></h2>
        <h3>ESNcountry: <b style="color: red">{{$user->esn_country}}</b></h3>
        <h4>ID/Passport: <u style="color: red">{{$user->document}}</u></h4>
        <a id="confirm" href="{{route('voting.device',$user)}}" class="btn btn-success">Confirm</a>
        <a href="{{route('voting.home')}}" class="btn btn-danger">Back</a>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready($(function focusOnConfirm() {
            $('#confirm').focus();
        }));
    </script>
@endsection