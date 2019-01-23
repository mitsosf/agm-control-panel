@extends('layouts.oc.master')

@section('content')
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12"></div>
        <div class="col-md-4 col-sm-6 col-xs-12" style="text-align: center">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-download"></i></span>

                <div class="info-box-content" style="text-align: center">

                    <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$deposit_amount}}<small>€ ({{$deposit_count}})</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12"></div>
    </div>
    <div class="row">
        <div class="container">
            <h4>Card deposits:</h4>
            <div class="box-body" style="background: white">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Amount</th>
                        <th class="hidden-xs">User</th>
                        <th class="hidden-xs">Country</th>
                        <th class="hidden-xs">Expiring</th>
                        <th>Approval</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($card_deposits as $transaction)
                        <tr style="text-align: center">
                            <td><a target="_blank" href="{{route('oc.transaction.show',$transaction->id)}}">{{$loop->index + 1}}</a></td>
                            <td><a class="btn btn-info" href="{{$transaction->proof}}" target="_blank">Proof</a></td>
                            @if($transaction->amount > 0)
                                <td style="text-align: center"><span class="label label-success">{{$transaction->amount}}</span></td>
                            @else
                                <td style="text-align: center"><span class="label label-danger">{{$transaction->amount}}</span></td>
                            @endif
                            <td class="hidden-xs"><a target="_blank" href="{{route('oc.user.show',$transaction->user)}}">{{$transaction->user->name.' '.$transaction->user->surname}}</a></td>
                            <td class="hidden-xs">{{$transaction->user->esn_country}}</td>
                            @if(\Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::createFromTimeString($transaction->created_at)->addDays(7), false) >= 0)
                                <td class="hidden-xs">{{\Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::createFromTimeString($transaction->created_at)->addDays(7), false)}} hrs</td>
                            @else
                                <td class="hidden-xs" style="color: red;">Expired</td>
                            @endif
                            <td>
                                <div class="row" style="text-align: center">
                                    <div class="col-md-4"><a class="btn btn-warning" href="{{route('oc.transaction.show',$transaction->id)}}"><i class="fa fa-eye"></i></a></div>
                                    <div class="col-md-4">
                                        <form action="{{route('oc.deposits.acquire',$transaction)}}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button class="btn btn-success" type="submit" onclick="return confirm('Are you sure you want to ACQUIRE this deposit?');"><i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-4">
                                        <form action="{{route('oc.deposits.refund',$transaction)}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to REFUND this deposit?');"><i class="fa fa-remove"></i>
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
                        <th class="hidden-xs">Expiring</th>
                        <th>Approval</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 3%">
        <div class="container">
            <h4>Cash deposits:</h4>
            <div class="box-body" style="background: white">
                <table id="example3" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th class="hidden-xs">Country</th>
                        <th class="hidden-xs">Checked-in by</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cash_deposits as $transaction)
                        @php
                            $checkiner = App\User::find($transaction->proof)
                        @endphp
                        <tr style="text-align: center">
                            <td><a target="_blank" href="{{route('oc.transaction.show',$transaction->id)}}">{{$loop->index + 1}}</a></td>

                            @if($transaction->amount > 0)
                                <td style="text-align: center"><span class="label label-success">{{$transaction->amount}}</span></td>
                            @else
                                <td style="text-align: center"><span class="label label-danger">{{$transaction->amount}}</span></td>
                            @endif
                            <td class="hidden-xs"><a target="_blank" href="{{route('oc.user.show',$transaction->user)}}">{{$transaction->user->name.' '.$transaction->user->surname}}</a></td>
                            <td class="hidden-xs">{{$transaction->user->esn_country}}</td>
                            <td><a href="{{route('oc.user.show',$checkiner)}}" target="_blank">{{$checkiner->name.' '.$checkiner->surname}}</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th class="hidden-xs">Country</th>
                        <th class="hidden-xs">Checked-in by</th>
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
        $(document).ready($(function focusOnSearch() {
            $('div.dataTables_filter input').focus();
        }));
    </script>
@endsection