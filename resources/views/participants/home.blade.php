@extends('layouts.participant.master')

@section('content')
    <h4>Dashboard:</h4>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <!-- small box -->
            <a href="{{route('participant.payment')}}">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Payment</h3>

                        <p>Pay AGM Thessaloniki 2019 participation fee</p>
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