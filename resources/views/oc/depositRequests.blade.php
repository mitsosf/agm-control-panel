@extends('layouts.oc.master')

@section('content')
    <h3>Deposit requests:</h3>

    <div class="row">
        <div class="container">
            <h4>Pending deposits:</h4>
            <div class="box">
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Creator</th>
                            <th>Date</th>
                            <th>Approve/Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($oc_transactions as $key=>$transaction)
                            @if($transaction->approved == 0)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{App\User::find($transaction->user_id)->name." ".App\User::find($transaction->user_id)->surname}}</td>
                                    <td>{{$transaction->created_at->diffForHumans()}}</td>
                                    <td>
                                        <div style="text-align: center" class="row">
                                            <div class="col-md-6">
                                                <a class="btn btn-success" href="{{route('oc.checkin.depositRequest.approve',$transaction)}}"><i class="fa fa-check"></i></a>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-danger" href="{{route('oc.checkin.depositRequest.delete',$transaction)}}"><i class="fa fa-remove"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Creator</th>
                            <th>Date</th>
                            <th>Approve/Delete</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <div class="row">
        <div class="container">
            <h4>Completed deposits:</h4>
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Created by</th>
                            <th>Approved by</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($oc_transactions as $key=>$transaction)
                            @if($transaction->approved == 1)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{App\User::find($transaction->user_id)->name." ".App\User::find($transaction->user_id)->surname}}</td>
                                    <td>{{App\User::find($transaction->proof)->name." ".App\User::find($transaction->proof)->surname}}</td>
                                    <td>{{$transaction->created_at->diffForHumans()}}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Created by</th>
                            <th>Approved by</th>
                            <th>Date</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.box -->
    </div>
@endsection

@section('js')
    <script>
        $(document).ready($(function () {
            $('#example1').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true
            })
        }));
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