@extends('layouts.oc.master')

@section('content')
    <h2>Approved</h2>
    <div class="container">
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover" >
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Paid</th>
                    <th>Room</th>
                    <th>Check-in</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->name." ".$user->surname}}</td>
                        <td>{{$user->esn_country}}</td>
                        @if($user->fee == 0)
                            <td style="text-align: center"><span class="label label-danger">No</span></td>
                        @else
                            <td style="text-align: center"><span class="label label-success">{{$user->fee}} €</span></td>
                        @endif
                        @if($user->rooming == 0)
                            <td style="text-align: center"><span class="label label-danger">No</span></td>
                        @else
                            <td style="text-align: center"><span class="label label-success">{{$user->rooming}}</span></td>
                        @endif
                        @if($user->checkin == 0)
                            <td style="text-align: center"><span class="label label-danger">No</span></td>
                        @else
                            <td style="text-align: center"><span class="label label-success">Yes</span></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Paid</th>
                    <th>Room</th>
                    <th>Check-in</th>
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