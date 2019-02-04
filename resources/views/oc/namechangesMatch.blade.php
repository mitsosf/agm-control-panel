@extends('layouts.oc.master')

@section('content')
    <div class="container">
        <h4>Change {{$user->name. ' '. $user->surname}} with: </h4>
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Country</th>
                    <th>Section</th>
                    <th>Match</th>
                </tr>
                </thead>
                <tbody>
                @foreach($matchable_users as $match)
                    @php

                    @endphp
                    <tr>
                        <td><a href="{{route('oc.user.show',$match)}}">{{$match->name." ".$match->surname}}</a></td>
                        <td>{{$match->esn_country}}</td>
                        <td>{{$match->section}}</td>
                        <td>
                            <div class="row" style="text-align: center">
                                <form action="{{route('oc.namechanges.match')}}" method="POST">
                                    <input id="giver" name="giver" type="hidden" value="{{$user->id}}">
                                    <input id="taker" name="taker" type="hidden" value="{{$match->id}}">
                                    @csrf
                                    <input type="submit" class="btn btn-success" value="Exchange">
                                </form>
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