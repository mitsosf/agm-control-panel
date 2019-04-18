@extends('layouts.checkin.master')

@section('content')
    <div style="text-align: center">
        <h3 style="margin-bottom: 2%">You are about to {!! $user->checkin==0?'<b style="color: green">checkin</b>':'<b style="color: red">checkout</b>' !!}:</h3>
        <a href="{{$user->photo}}" target="_blank"><img src="{{$user->photo}}" alt="User photo" width="10%"></a>
        <h1>Name: <u>{{$user->name.' '.$user->surname}}</u></h1>
        <h2>ESN country: <b>{{$user->esn_country}}</b></h2>
        <h2>T-shirt: <b>{{$user->tshirt}}</b></h2>
        <h4>Section: <b>{{$user->section}}</b></h4>
        <h4>ID/Passport: <u>{{$user->document}}</u></h4>
        <div class="row" style="margin-bottom: 2%">


            @if($debt['amount']!==0)
                <h1 style="color: red">Owes: <u>{{$debt['amount']}}</u>€</h1>
            @endif
            @if($user->bracelet_id == 1)
                <img src="{{asset('images/bracelets/purple.png')}}" alt="Purple bracelet" width="30%">
            @elseif($user->bracelet_id == 2)
                <img src="{{asset('images/bracelets/blue.png')}}" alt="Purple bracelet" width="30%">
            @elseif($user->bracelet_id == 3)
                <img style="margin-bottom: 1%" src="{{asset('images/bracelets/purple.png')}}" alt="Purple bracelet" width="30%"><br>
                <img src="{{asset('images/bracelets/blue.png')}}" alt="Purple bracelet" width="30%">
            @else
                <h4 style="color:red">Error in bracelet allocation, contact the OC</h4>
            @endif
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <form action="{{route('checkin.checkin')}}" method="POST">
                        <input id="hotel" name="hotel" type="hidden" value="{{$hotel->id}}">
                        <input type="hidden" name="user" id="user" value="{{$user->id}}">
                        <div class="form-group">
                            <label for="proof"></label>
                            <textarea id="proof" name="proof" class="form-control"
                                      rows="2">{{is_null($user->transactions->where('type','checkin')->first())?'':$user->transactions->where('type','checkin')->first()->proof}}</textarea>
                        </div>
                        @csrf
                        <input class="btn btn-success" type="submit" id="confirm" value="Confirm">
                        <a href="{{route('checkin.hotel', $hotel)}}" class="btn btn-danger">Back</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready($(function focusOnConfirm() {
            $('#confirm').focus();
        }));
    </script>
@endsection