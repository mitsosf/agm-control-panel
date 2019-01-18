@extends('layouts.participant.master')

@section('content')
    @if(Session::get('paid_fee') == 1)
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">You have successfully paid the event fee!!</h3>

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
                <div class="box box-warning">
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
                            <p>Pay AGM Thessaloniki 2019 participation fee</p>
                        @endif
                    </div>
                    <div class="icon">
                        <i class="fa fa-eur"></i>
                    </div>
                    <div class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></div>
                </div>
            </a>
        </div>
        @if(env('EVENT_DEPOSITS'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <!-- small box -->
                <a href="{{route('participant.deposit')}}">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>Deposit</h3>
                            @if($user->spot_status === 'paid')
                                <p>You have successfully paid the deposit</p>
                            @else
                                <p>Pay the event deposit</p>
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
    </div>
@endsection