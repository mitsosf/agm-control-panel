@extends('layouts.oc.master')

@section('content')
    <div class="container">
        <h4>Invitations: <a class="btn btn-warning" href="{{route('oc.invitations.sync')}}">ERS <i
                        class="fa fa-refresh"></i></a></h4>
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Fee</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invitations as $user)
                    <tr>
                        <td><a href="{{route('oc.user.show',$user)}}">{{$user->name." ".$user->surname}}</a></td>
                        <td>{{$user->esn_country}}</td>
                        <td>{{$user->section}}</td>
                        @if($user->fee != "0")
                            <td style="text-align: center"><span class="label label-success">{{$user->fee}} €</span></td>
                        @else
                            <td style="text-align: center"><span class="label label-danger">No</span></td>
                        @endif
                        <td>
                            <div class="row" style="text-align: center">
                                <a class="btn btn-success" href="{{route('oc.invitation.send', $user)}}">Send <i
                                            class="fa fa-arrow-right"></i></a>
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
                    <th>Fee</th>
                    <th>Actions</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <h4>Sent Invitations: </h4>
        <div class="box-body" style="background: white">
            <table id="example3" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Fee</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sent_invitations as $user)
                    <tr>
                        <td><a href="{{route('oc.user.show',$user)}}">{{$user->name." ".$user->surname}}</a></td>
                        <td>{{$user->esn_country}}</td>
                        <td>{{$user->section}}</td>
                        @if($user->fee != "0")
                            <td style="text-align: center"><span class="label label-success">{{$user->fee}} €</span></td>
                        @else
                            <td style="text-align: center"><span class="label label-danger">No</span></td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Fee</th>
                </tr>
                </tfoot>
            </table>
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