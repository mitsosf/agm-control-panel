@extends('layouts.oc.master')

@section('content')
    <div class="container">
        <h4>Checkiners:</h4>
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Cash</th>
                    <th class="hidden-xs">Deposited</th>
                    <th class="hidden-xs">All</th>
                </tr>
                </thead>
                <tbody>
                @foreach($checkiners as $checkiner)
                    <tr>
                        <td>{{$checkiner->name.' '.$checkiner->surname}}</td>
                        <td><b>{{$funds['cash'][$checkiner->id]}}€</b></td>
                        <td>{{$funds['deposited'][$checkiner->id]}}€</td>
                        <td>{{$funds['all'][$checkiner->id]}}€</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Cash</th>
                    <th class="hidden-xs">Deposited</th>
                    <th class="hidden-xs">All</th>
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