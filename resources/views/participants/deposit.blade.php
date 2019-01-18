@extends('layouts.participant.master')

@section('content')
    <h4>Deposit payment</h4>
    <div class="container" style="text-align: center;font-family: 'Lato'">
        <div class="row" style="padding-top: 3%">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Participant details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tr>
                                <td><b>Name:</b></td>
                                <td>{{$user->name.' '.$user->surname}}</td>
                            </tr>
                            <tr>
                                <td><b>Section:</b></td>
                                <td>{{$user->section}}</td>
                            </tr>
                            <tr>
                                <td><b>Country</b></td>
                                <td>{{$user->esn_country}}</td>
                            </tr>
                            <tr>
                                <td><b>Status</b></td>
                                <td>
                                    @switch($deposit_check)
                                        @case(0)
                                        <b style="color: orange">Payment pending</b>
                                        @break

                                        @case(1)
                                        <b style="color: green">Deposit paid!</b>
                                        @break

                                        @default
                                        <b style="color: red">Something went wrong, contact the OC{{$deposit_check}}</b>
                                    @endswitch
                                </td>
                            </tr>
                            @if(!$deposit_check)
                                <tr>
                                    <td><b></b></td>
                                    <td>
                                        <div style="text-align: left">
                                            <form class="payment-card-form" method="POST" action="{{route('participant.parseToken')}}">
                                                <script type="text/javascript" class="everypay-script"
                                                        src="https://button.everypay.gr/js/button.js"
                                                        data-key="{{env('EVERYPAY_PUBLIC_KEY')}}"
                                                        data-amount="5000"
                                                        data-locale="en"
                                                        data-description="{{Auth::user()->name.' '.Auth::user()->surname}} - AGM Thessaloniki 2019 - Deposit"
                                                        @if(env('APP_ENV','production') === 'local')
                                                        data-sandbox="1"
                                                        @endif
                                                >
                                                </script>
                                                @csrf
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.box -->
            <div class="col-md-4">
            </div>
        </div>
        <div style="background: rgba(34,0,171,0.27); margin-right: 30%;margin-left: 30%;">

        </div>
        @if($error)
            <h3 style="color: red">{{$error}}</h3>
        @endif

        @if(!$deposit_check)
            <div>
                <p style="color: grey">Encountering issues? You can also pay in person.</p>
            </div>
        @endif
    </div>
@endsection

@section('css')
    <style>
        #bank_acc {
            background: none !important;
            border: none;
            padding: 0 !important;

            /*optional*/
            font-family: arial, sans-serif; /*input has OS specific font-family*/
            color: #069;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
@endsection