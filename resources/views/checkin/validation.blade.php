@extends('layouts.checkin.master')

@section('content')
    <div style="text-align: center">
        <h1 style="margin-bottom: 2%">You are about to {!! $user->checkin==0?'<b style="color: green">checkin</b>':'<b style="color: red">checkout</b>' !!}:</h1>
        <a href="{{$user->photo}}" target="_blank"><img src="{{$user->photo}}" alt="User photo" width="10%"></a>
        <h1>Name: <u>{{$user->name.' '.$user->surname}}</u></h1>
        <h2>Section: <b>{{$user->section}}</b></h2>
        <h3>ESNcountry: <b>{{$user->esn_country}}</b></h3>
        <h4>ID/Passport: <u>{{$user->document}}</u></h4>
        <div class="row" style="margin-bottom: 2%">


            @if($debt['amount']!==0)
                <h1 style="color: red">Owes: <u>{{$debt['amount']}}</u>€</h1>
            @endif
            @if($user->bracelet_id == 1)
                <img src="{{asset('images/bracelets/purple.png')}}" alt="Purple bracelet" width="30%">
            @elseif($user->bracelet_id == 2)
                <img src="{{asset('images/bracelets/blue.png')}}" alt="Purple bracelet" width="30%">
            @else
                <h4 style="color:red">Error in bracelet allocation, contact the OC</h4>
            @endif
        </div>
        <a id="confirm" href="{{route('checkin.checkin', ['hotel'=>$hotel,'user'=>$user])}}" class="btn btn-success">Confirm</a>
        <a href="{{route('checkin.home')}}" class="btn btn-danger">Back</a>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready($(function focusOnConfirm() {
            $('#confirm').focus();
        }));
    </script>
@endsection