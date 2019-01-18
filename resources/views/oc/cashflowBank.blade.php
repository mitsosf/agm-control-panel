@extends('layouts.oc.master')

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <h4>Bank Total:</h4>
            <div class="info-box">
                <span class="info-box-icon bg-light-blue"><i class="fa fa-money"></i></span>
                <div class="info-box-content" style="text-align: center">
                    <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$pending_cash_income + $confirmed_cash_income}}<small>€ ({{$pending_cash_count + $confirmed_cash_count}})</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <h4>Approved:</h4>
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>

                <div class="info-box-content" style="text-align: center">

                    <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$confirmed_cash_income}}<small>€ ({{$confirmed_cash_count}})</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <h4>Pending:</h4>
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-warning"></i></span>

                <div class="info-box-content" style="text-align: center">

                    <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$pending_cash_income}}<small>€ ({{$pending_cash_count}})</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <h4>Debt:</h4>
            <a href="{{route('oc.cashflow.debts')}}">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-gavel"></i></span>

                    <div class="info-box-content" style="text-align: center">

                        <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$debt_amount}}<small>€ ({{$debt_count}})</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </a>
        </div>
    </div>
    <div class="row">
        <div class="container">
            <h4>Pending Bank transactions: <a class="btn btn-warning" href="{{route('oc.cashflow.bank.sync')}}">ERS <i class="fa fa-refresh"></i></a></h4>
            <div class="box-body" style="background: white">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Amount</th>
                        <th class="hidden-xs">User</th>
                        <th class="hidden-xs">Country</th>
                        <th class="hidden-xs">Date</th>
                        <th>Approval</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--TODO Delete and replace with foreach below when API ready--}}
                    {{--@foreach($pending_users as $user)
                        <tr style="text-align: center">
                            <td><a href="{{route('oc.user.show',$user->id)}}">{{$loop->index + 1}}</a></td>
                            <td><a class="btn btn-info" href="#" target="_blank">Proof</a></td>
                            @if($user->fee > 0)
                                <td style="text-align: center"><span class="label label-success">{{env('EVENT_FEE')}}</span></td>
                            @else
                                <td style="text-align: center"><span class="label label-danger">Contact me!</span></td>
                            @endif
                            <td class="hidden-xs"><a href="{{route('oc.user.show',$user)}}">{{$user->name.' '.$user->surname}}</a></td>
                            <td class="hidden-xs">{{$user->esn_country}}</td>
                            <td class="hidden-xs">{{$user->created_at->format('d/m/Y')}}</td>
                            <td>
                                <div class="row" style="text-align: center">
                                    <div class="col-md-4"><a class="btn btn-warning" href="{{route('oc.user.show',$user)}}"><i class="fa fa-eye"></i></a></div>
                                    <div class="col-md-4"><a class="btn btn-success" href="{{route('oc.transaction.create',$user)}}"><i class="fa fa-check"></i></a></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach--}}
                    @foreach($pending_transactions as $transaction)
                        <tr style="text-align: center">
                            <td><a href="{{route('oc.transaction.show',$transaction->id)}}">{{$loop->index + 1}}</a></td>
                            <td><a class="btn btn-info" href="{{$transaction->proof}}" target="_blank">Proof</a></td>
                            @if($transaction->amount > 0)
                                <td style="text-align: center"><span class="label label-success">{{$transaction->amount}}</span></td>
                            @else
                                <td style="text-align: center"><span class="label label-danger">{{$transaction->amount}}</span></td>
                            @endif
                            <td class="hidden-xs"><a href="{{route('oc.user.show',$transaction->user)}}">{{$transaction->user->name.' '.$transaction->user->surname}}</a></td>
                            <td class="hidden-xs">{{$transaction->user->esn_country}}</td>
                            <td class="hidden-xs">{{\Carbon\Carbon::createFromTimeString($transaction->created_at)->format('d/m/Y')}}</td>
                            <td>
                                <div class="row" style="text-align: center">
                                    <div class="col-md-4"><a class="btn btn-warning" href="{{route('oc.transaction.show',$transaction->id)}}"><i class="fa fa-eye"></i></a></div>
                                    <div class="col-md-4">
                                        <a class="btn btn-success" href="{{route('oc.transaction.approve.show', $transaction)}}"><i class="fa fa-check"></i></a>
                                    </div>
                                    <div class="col-md-4">
                                        <form action="{{route('oc.transaction.delete',$transaction->id)}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to DELETE this transaction?');"><i class="fa fa-remove"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Amount</th>
                        <th class="hidden-xs">User</th>
                        <th class="hidden-xs">Country</th>
                        <th class="hidden-xs">Date</th>
                        <th>Approval</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 5%">
        <div class="container">
            <h4>Approved Bank transactions:</h4>
            <div class="box-body" style="background: white">
                <table id="example3" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th class="hidden-xs">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($confirmed_transactions as $transaction)
                        <tr style="text-align: center">
                            <td><a href="{{route('oc.transaction.show',$transaction->id)}}">{{$loop->index + 1}}</a></td>
                            <td>{{$transaction->type}}</td>
                            @if($transaction->amount > 0)
                                <td style="text-align: center"><span class="label label-success">{{$transaction->amount}}</span></td>
                            @else
                                <td style="text-align: center"><span class="label label-danger">{{$transaction->amount}}</span></td>
                            @endif
                            <td><a href="{{route('oc.user.show',$transaction->user)}}">{{$transaction->user->name.' '.$transaction->user->surname}}</a></td>
                            <td class="hidden-xs">{{\Carbon\Carbon::createFromTimeString($transaction->created_at)->format('d/m/Y')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>User</th>
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
    <script>
        $(document).ready($(function () {
            $('#example3').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true
            })
        }));
    </script>
@endsection