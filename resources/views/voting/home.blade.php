@extends('layouts.voting.master')

@section('content')
    <div class="container">
        <h4>Section Delegates:</h4>
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
                @foreach($delegates as $delegate)
                    <tr>
                        @if($delegate->delegate == 1)
                            <td><a class="btn btn-success" href="{{route('voting.validate',$delegate)}}">Give
                                    device</a></td>
                        @elseif($delegate->delegate == 2)
                            <td><a class="btn btn-danger" href="{{route('voting.validate',$delegate)}}">Return
                                    device</a></td>
                        @endif
                        <td>{{$delegate->name." ".$delegate->surname}}</td>
                        <td>{{$delegate->esn_country}}</td>
                        <td class="hidden-xs">{{$delegate->section}}</td>
                        <td>{{$delegate->document}}</td>
                        <td>{{$delegate->esncard}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Device</th>
                    <
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