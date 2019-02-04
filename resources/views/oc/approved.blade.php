@extends('layouts.oc.master')

@section('content')
    <div class="container">
        <h4>Approved ESNers: <a class="btn btn-warning" href="{{route('oc.approved.sync')}}">ERS <i class="fa fa-refresh"></i></a></h4>
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Paid</th>
                    <th class="hidden-xs">Room</th>
                    <th class="hidden-xs">Check-in</th>
                    <th class="hidden-xs">Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    @php
                        $transaction = $user->transactions->where('type','fee')->first();
                    @endphp
                    <tr>
                        <td><a href="{{route('oc.user.show',$user)}}">{{$user->name." ".$user->surname}}</a></td>
                        <td>{{$user->esn_country}}</td>
                        @if(isset($transaction))
                            @if($transaction->approved == 0)
                                <td style="text-align: center"><span class="label label-danger">No</span></td>
                            @else
                                <td style="text-align: center"><span class="label label-success">{{$transaction->amount}} €</span></td>
                            @endif
                        @else
                            <td style="text-align: center"><span class="label label-danger">No</span></td>
                        @endif
                        @if($user->rooming == 0)
                            <td style="text-align: center" class="hidden-xs"><span class="label label-danger">No</span></td>
                        @else
                            <td style="text-align: center" class="hidden-xs"><span class="label label-success">{{$user->rooming}}</span></td>
                        @endif
                        @if($user->checkin == 0)
                            <td style="text-align: center" class="hidden-xs"><span class="label label-danger">No</span></td>
                        @else
                            <td style="text-align: center" class="hidden-xs"><span class="label label-success">Yes</span></td>
                        @endif
                        <td class="hidden-xs">{{\Carbon\Carbon::createFromTimeString($user->created_at)->diffForHumans()}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Paid</th>
                    <th class="hidden-xs">Room</th>
                    <th class="hidden-xs">Check-in</th>
                    <th class="hidden-xs">Date</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
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