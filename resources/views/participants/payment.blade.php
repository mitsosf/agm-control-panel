@extends('layouts.participant.master')

@section('content')
    <h4>Fee payment</h4>
    <div class="container" style="text-align: center;font-family: 'Lato'">
        <div class="row">
            <img src="{{asset('images/why_card.jpg')}}" alt="Why pay with card" style="max-width:100%">
        </div>
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
                                    @switch($user->spot_status)
                                        @case('approved')
                                        <b style="color: orange">Payment pending</b>

                                        @break

                                        @case('paid')
                                        <b style="color: green">Paid <a target="_blank" href="{{route('participant.generateProof')}}">(Proof)</a></b>
                                        @break

                                        @default
                                        <b style="color: red">Spot approval pending.<br>Please contact your NR.</b>
                                    @endswitch
                                </td>
                            </tr>
                            @if($user->spot_status === 'approved')
                                <tr>
                                    <td><b></b></td>
                                    <td>
                                        <div style="text-align: left">
                                            <form class="payment-card-form" method="POST" action="{{route('participant.validateCard')}}">
                                                <script type="text/javascript" class="everypay-script"
                                                        src="https://button.everypay.gr/js/button.js"
                                                        data-key="{{env('EVERYPAY_PUBLIC_KEY')}}"
                                                        data-amount="22200"
                                                        data-locale="en"
                                                        data-description="{{Auth::user()->name.' '.Auth::user()->surname}} - AGM Thessaloniki 2019 - Participation fee"
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

        @if($user->spot_status === 'approved')
            <div class="row">
                <h5 style="color: red;">Notice: Please do not pay if your spot hasn't been approved by your NR</h5>
            </div>
            <div>
                <p style="color: grey">Encountering issues? <!-- Button trigger modal -->
                    <button type="button" id="bank_acc" data-toggle="modal" data-target="#exampleModal">
                        Pay via bank transfer
                    </button>
                </p>
            </div>
        @endif
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bank details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Please transfer exactly <b>222€</b> to this account:</p>

                    <p>PIRAEUS BANK</p>
                    <p>BIC: PIRBGRAA</p>
                    <p>Bank Address: 4 AMERIKIS STREET, ATHENS, 10564</p>
                    <p>IBAN: GR9601722290005229093337111</p>
                    <p>Beneficiary: FEDERATION OF ERASMUS STUDENT NETWORK - GREECE</p>
                    <p>ESN Greece Address: Patision 42, Athens, 10682</p>
                    <p>Reference: <b style="color: red">{{$bank_reference}}</b></p>

                    <p style="color: red">Please make sure to cover all banking fees.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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