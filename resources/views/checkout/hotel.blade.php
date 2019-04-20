@extends('layouts.checkout.master')

@section('content')
    <h2>All hotels</h2>
    <div style="text-align: center"><h2 style="color: green">Checked
            out: {{$checkedOut->count().'/'.$residents->count()}} ({{floor($checkedOut->count()/$residents->count()*100)}}%)</h2></div>
    <div style="text-align: center"><h3 style="color: green">Deposits: {{($checkedOut->count()*50).'/'.$residents->count()*50}} €</h3></div>
    <div class="container">
        <div class="box-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Check-in</th>
                    <th>Name</th>
                    <th class="hidden-xs">ESNcard</th>
                    <th class="hidden-xs">ID</th>
                    <th class="hidden-xs">Country</th>
                    <th class="hidden-xs">Section</th>
                </tr>
                </thead>
                <tbody>
                @foreach($residents as $resident)
                    <tr>
                        @if($resident->checkin == 1)
                            <td><a href="{{route('checkout.validate',['hotel' => $hotel,'user' => $resident])}}"
                                   class="btn btn-primary">Check-out</a></td>
                        @else
                            <td><a href="{{route('checkout.validate',['hotel' => $hotel,'user' => $resident])}}"
                                   class="btn btn-danger">Uncheck-out</a></td>
                        @endif
                        <td>{{$resident->name." ".$resident->surname}}</td>
                        <td class="hidden-xs">{{$resident->esncard}}</td>
                        <td class="hidden-xs">{{$resident->document}}</td>
                        <td class="hidden-xs">{{$resident->esn_country}}</td>
                        <td class="hidden-xs">{{$resident->section}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Check-in</th>
                    <th>Name</th>
                    <th class="hidden-xs">ESNcard</th>
                    <th class="hidden-xs">ID</th>
                    <th class="hidden-xs">Country</th>
                    <th class="hidden-xs">Section</th>
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
        $(document).ready($(function focusOnSearch() {
            $('div.dataTables_filter input').focus();
        }));
    </script>
@endsection