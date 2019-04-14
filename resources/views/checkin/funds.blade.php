@extends('layouts.checkin.master')

@section('content')
    <h3>My funds:</h3>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Cash</th>
                    <th scope="col">Deposited</th>
                    <th scope="col">All</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><h4>{{$funds['cash']}}€</h4></td>
                    <td><h4>{{$funds['deposited']}}€</h4></td>
                    <td><h4>{{$funds['all']}}€</h4></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4" style="text-align: center">
            <a class="btn btn-success" href="{{route('checkin.funds.createRequest.show')}}"><span><i class="fa fa-plus"></i></span> Deposit request</a>
        </div>
    </div>
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
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($oc_transactions as $key=>$transaction)
                            @if($transaction->approved == 0)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$transaction->amount}}</td>
                                    <td>{{$transaction->created_at->diffForHumans()}}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
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
                                    <td>{{App\User::find($transaction->comments)->name." ".App\User::find($transaction->comments)->surname}}</td>
                                    <td>{{$transaction->created_at->diffForHumans()}}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
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