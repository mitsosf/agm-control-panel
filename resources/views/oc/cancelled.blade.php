@extends('layouts.oc.master')

@section('content')
    <div class="container">
        <h4>Cancelled spots: <a class="btn btn-warning" href="{{route('oc.cancelled.sync')}}">ERS <i class="fa fa-refresh"></i></a></h4>
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><a href="{{route('oc.user.show',$user)}}">{{$user->name." ".$user->surname}}</a></td>
                        <td>{{$user->esn_country}}</td>
                        <td>{{$user->section}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- /.box -->
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