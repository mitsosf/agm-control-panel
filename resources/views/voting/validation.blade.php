@extends('layouts.voting.master')

@section('content')
    <div style="text-align: center">
        <h1 style="margin-bottom: 2%">You are about to {!! $delegation->given?'<b style="color: red">take</b> a device/s from':'<b style="color: green">give</b> a device/s to' !!}:</h1>
        <a href="{{$user->photo}}" target="_blank"><img src="{{$user->photo}}" alt="User photo" width="10%"></a>
        <h1>Name: <u>{{$user->name.' '.$user->surname}}</u></h1>
        @if(!is_null($delegations))
            <h2>Provide user with <div style="color: red">{{$delegations->count().' '.$delegation->type}} devices</div></h2>
        @endif
        <h2>ID/Passport: <u>{{$user->document}}</u></h2>
        <h2>Section: <b>{{$user->section}}</b></h2>
        <h3>ESNcountry: <b>{{$user->esn_country}}</b></h3>
        <a id="confirm" href="{{route('voting.device',$delegation_id)}}" class="btn btn-success">Confirm</a>
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