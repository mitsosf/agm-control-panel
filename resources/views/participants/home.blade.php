@extends('layouts.app')

@section('content')
    <div class="container" style="text-align: center;font-family: 'Lato'">
        <div style="text-align: center">
            <img style=" max-width: 15%;" src="https://agmthessaloniki.org/logo_color.png" alt="AGM Thessaloniki 2019">
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4" style="background: rgba(34,0,171,0.27); border-radius: 25px;">
                <h3 style="padding-top: 3%"><u>Participant details:</u></h3>
                <h4>Name: {{$user->name.' '.$user->surname}}</h4>
                <h4>Section: {{$user->section}}</h4>
                <h4>Status:
                    @if($user->spot_status)
                        <b style="color: green">Approved</b>
                    @else
                        <b style="color: red">Pending</b>
                    @endif
                </h4>
                <div style="padding-bottom: 2%">
                    <form class="payment-card-form" method="POST" action="{{route('participant.validateCard')}}">
                        <script type="text/javascript" class="everypay-script"
                                src="https://button.everypay.gr/js/button.js"
                                data-key="{{env('EVERYPAYPUBLICKEY')}}"
                                data-amount="22000"
                                data-locale="en"
                                data-description="{{Auth::user()->name.' '.Auth::user()->surname}} - AGM Thessaloniki 2019 - Participation fee"
                                data-sandbox="1">

                        </script>
                        @csrf
                    </form>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div style="background: rgba(34,0,171,0.27); margin-right: 30%;margin-left: 30%;">

        </div>
        @if($error)
            <h3 style="color: red">{{$error}}</h3>
        @endif
        <div>
            <u style="color: lightgrey">Encountering issues? Pay with bank transfer (Plemb)</u>
        </div>
    </div>
@endsection