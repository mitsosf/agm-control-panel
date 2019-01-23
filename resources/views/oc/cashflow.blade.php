@extends('layouts.oc.master')

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-eur"></i></span>

                <div class="info-box-content" style="text-align: center">

                    <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$income}}<small>€  ({{$transactions_count}})</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{route('oc.cashflow.card')}}">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-credit-card"></i></span>

                    <div class="info-box-content" style="text-align: center">

                        <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$card_income}}<small>€  ({{$card_count}})</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{route('oc.cashflow.bank')}}">
                <div class="info-box">
                    <span class="info-box-icon bg-fuchsia"><i class="fa fa-money"></i></span>

                    <div class="info-box-content" style="text-align: center">

                        <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$cash_income}}<small>€  ({{$cash_count}})</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{route('oc.cashflow.debts')}}">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-gavel"></i></span>

                    <div class="info-box-content" style="text-align: center">

                        <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$deposit_count}}<small> ({{$deposit_amount}}€)</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
    </div>
    <div class="row">
        <div class="container">
            <h4>All income:</h4>
            <div class="box-body" style="background: white">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th>Country</th>
                        <th class="hidden-xs">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr style="text-align: center">
                            <td><a href="{{route('oc.transaction.show',$transaction->id)}}" target="_blank">{{$loop->index + 1}}</a></td>
                            @if($transaction->amount > 0)
                                <td style="text-align: center">
                                    <span class="label label-success">{{$transaction->amount}}</span>
                                    @if(is_null($transaction->comments))
                                        <span class="label label-info"><i class="fa fa-credit-card"></i></span>
                                    @else
                                        <span class="label label-default"><i class="fa fa-money"></i></span>
                                    @endif
                                </td>
                            @else
                                <td style="text-align: center"><span class="label label-danger">{{$transaction->amount}}</span></td>
                            @endif
                            <td><a href="{{route('oc.user.show',$transaction->user)}}" target="_blank">{{$transaction->user->name.' '.$transaction->user->surname}}</a></td>
                            <td>{{$transaction->user->esn_country}}</td>
                            <td class="hidden-xs">{{\Carbon\Carbon::createFromTimeString($transaction->created_at)->format('d/m/Y')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th>Country</th>
                        <th class="hidden-xs">Date</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready($(function () {
            $('#example2').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true
            })
        }));
        $(document).ready($(function focusOnSearch() {
            $('div.dataTables_filter input').focus();
        }));
    </script>
@endsection