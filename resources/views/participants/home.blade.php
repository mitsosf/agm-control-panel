@extends('layouts.participant.master')

@section('content')
    <h4>Dashboard:</h4>
    @if(Session::get('paid_fee') == 1)
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Success!</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            You have successfully paid the fee!
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
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
    </div>
@endsection