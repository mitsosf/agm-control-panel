@extends('layouts.oc.master')

@section('content')
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12"></div>
        <div class="col-md-4 col-sm-6 col-xs-12" style="text-align: center">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-gavel"></i></span>

                <div class="info-box-content" style="text-align: center">

                    <span class="info-box-number" style="height: 80px; line-height: 80px; text-align: center;">{{$debt_amount}}<small>€ ({{$debt_count}})</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12"></div>
    </div>
    <div class="row">
        <div class="container">
            <h4>Debt:</h4>
            <div class="box-body" style="background: white">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th class="hidden-xs">Country</th>
                        <th class="hidden-xs">Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($debts as $transaction)
                        <tr>
                            <td style="text-align: center"><a target="_blank" href="{{route('oc.transaction.show',$transaction->id)}}">{{$loop->index + 1}}</a></td>
                            @if($transaction->approved == 0)
                                <td style="text-align: center"><span class="label label-danger">{{$transaction->amount}}</span></td>
                            @else
                                <td style="text-align: center"><span class="label label-success">{{$transaction->amount}}</span></td>
                            @endif
                            <td><a target="_blank" href="{{route('oc.user.show',$transaction->user)}}">{{$transaction->user->name.' '.$transaction->user->surname}}</a></td>
                            <td class="hidden-xs">{{$transaction->user->esn_country}}</td>
                            <td class="hidden-xs">{{\Carbon\Carbon::createFromTimeString($transaction->created_at)->format('d/m/Y')}}</td>
                            <td>
                                <div class="row" style="text-align: center">
                                    <div class="col-md-6"><a class="btn btn-info" href="{{route('oc.debt.edit.show',$transaction->id)}}"><i class="fa fa-edit"></i></a></div>
                                    <div class="col-md-6">
                                        <form action="{{route('oc.debt.delete',$transaction)}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to DELETE this debt?');"><i class="fa fa-remove"></i>
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
                        <th>Amount</th>
                        <th>User</th>
                        <th class="hidden-xs">Country</th>
                        <th class="hidden-xs">Date</th>
                        <th>Actions</th>
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