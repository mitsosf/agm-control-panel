@extends('layouts.oc.master')

@section('content')
    <div class="container">
        <h4>Namechanges: <a class="btn btn-warning" href="{{route('oc.namechanges.sync')}}">ERS <i class="fa fa-refresh"></i></a></h4>
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><a href="{{route('oc.user.show',$user)}}">{{$user->name." ".$user->surname}}</a></td>
                        <td>{{$user->esn_country}}</td>
                        <td>{{$user->section}}</td>
                        <td>
                            <div class="row" style="text-align: center">
                                <a class="btn btn-success" href="{{route('oc.namechanges.match.show', $user)}}"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
        <h4 style="margin-top: 5%">Completed namechanges: </h4>
        <div class="box-body" style="background: white">
            <table id="example3" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Changed with</th>
                </tr>
                </thead>
                <tbody>
                @foreach($completed_namechanges as $user)
                    @php
                    $taker = \App\User::find(explode('- ',$user->comments)[2]);
                    @endphp
                    <tr>
                        <td><a href="{{route('oc.user.show',$user)}}">{{$user->name." ".$user->surname}}</a></td>
                        <td>{{$user->esn_country}}</td>
                        <td>{{$user->section}}</td>
                        <td><a href="{{route('oc.user.show',$taker)}}">{{$taker->name." ".$taker->surname." - ".$taker->esn_country." - ".$taker->section}}</a></td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Changed with</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
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