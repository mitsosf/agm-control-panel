@extends('layouts.voting.master')

@section('content')
    <div class="container">
        <h5 style="text-align: center; color: green;">{{$givenCount.'/'.$devicesCount}} ({{$ratio}}%)</h5>
        <h4>{{$round->name}}' Devices:</h4>
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Device</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th class="hidden-xs">Section</th>
                    <th>ID</th>
                    <th>ESNcard</th>
                </tr>
                </thead>
                <tbody>
                @foreach($delegations as $delegation)
                    @if(!is_null($delegation))
                        @if($delegation->given == 0)
                            <tr>
                                <td><a class="btn btn-success" href="{{route('voting.validate',$delegation->id)}}">Give
                                        device</a></td>
                                <td>{{$delegation->user->name." ".$delegation->user->surname}}</td>
                                <td>{{$delegation->user->esn_country}}</td>
                                <td class="hidden-xs">{{$delegation->user->section}}</td>
                                <td>{{$delegation->user->document}}</td>
                                <td>{{$delegation->user->esncard}}</td>
                            </tr>
                        @endif
                    @endif
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Device</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th class="hidden-xs">Section</th>
                    <th>ID</th>
                    <th>ESNcard</th>
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