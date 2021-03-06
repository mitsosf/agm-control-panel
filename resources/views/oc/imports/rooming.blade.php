@extends('layouts.oc.master')

@section('content')
    <h2>Rooming import</h2>
    <form action="{{route('oc.import.rooming')}}" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="data" id="data">
        @csrf
        <input class="btn btn-success" type="submit" value="Upload" name="submit">
        <a class="btn btn-danger" href="{{route('oc.home')}}">Back</a>
    </form>
@endsection