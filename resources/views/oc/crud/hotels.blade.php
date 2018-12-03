@extends('layouts.oc.master')

@section('content')
    <h2>CRUD - Hotels</h2>
    <div class="container">
        <div class="box-body" style="background: white">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach($hotels as $hotel)
                    <tr>
                        <td style="text-align: center">{{$hotel->id}}</td>
                        <td style="text-align: center"><a href="#"><u><b>{{$hotel->name}}</b></u></a></td>
                        <td style="text-align: center"><a href="{{route('oc.crud.hotels.edit.show', $hotel->id)}}" class="btn btn-warning">Edit</a></td>
                        <td style="text-align: center"><a href="{{route('oc.crud.hotels.delete', $hotel->id)}}" class="btn btn-danger">Delete</a></td></td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
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