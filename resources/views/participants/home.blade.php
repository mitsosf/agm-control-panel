@extends('layouts.participant.master')

@section('content')
    @if(Session::get('paid_fee') == 1)
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">You have successfully paid the event fee & secured your spot!!</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                Get ready for the greatest event of the year!
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    @endif
    @if(Session::get('paid_deposit') == 1)
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">You have successfully paid the event deposit!!</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                You will soon receive an email with the proof of payment attached!
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    @endif
    @if(!is_null($debt))
        <div class="row">
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Banking fees</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <p>Your bank transfer has charged us with <b>{{$debt->amount}}€ </b>. ¯\_(ツ)_/¯</p>
                        <p>You will be asked to cover this during check-in!</p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="{{route('participant.payment')}}">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Fee</h3>
                        @if($user->spot_status === 'paid')
                            <p>You have successfully paid the fee</p>
                        @else
                            @if(env('EVENT_PAYMENTS',0))
                                <p>Pay AGM Thessaloniki 2019 participation fee</p>
                            @else
                                <p>Payments are closed</p>
                            @endif
                        @endif
                    </div>
                    <div class="icon">
                        <i class="fa fa-eur"></i>
                    </div>
                    <div class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></div>
                </div>
            </a>
        </div>
        @if(env('EVENT_DEPOSITS',0) && $user->spot_status === 'paid')
            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <a href="{{route('participant.deposit')}}">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>Deposit</h3>
                            @if($deposit_check == "1")
                                <p>You have successfully paid the deposit</p>
                            @elseif($deposit_check == "0")
                                <p>Pay the event deposit</p>
                            @else
                                <p>Something went wrong, contact the OC (Error: {{$deposit_check}})</p>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <div class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></div>
                    </div>
                </a>
            </div>
        @endif
        @if($user->transactions->where('type','checkin')->count()>0)
            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <a target="_blank" href="{{route('participant.certificate')}}">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>Certificate</h3>
                            <p>Get your certificate of attendance</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-file-text"></i>
                        </div>
                        <div class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></div>
                    </div>
                </a>
            </div>
        @endif
    </div>
@endsection