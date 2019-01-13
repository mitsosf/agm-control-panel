@extends('layouts.oc.master')

@section('content')
    <h4>User info:</h4>
    <p>Name: {{$user->name. " ". $user->surname}}</p>
    <p>Transaction ID: <a href="{{route('oc.transaction.show',$transaction->id)}}">{{$transaction->id}}</a></p>
    <p>Price: {{env('EVENT_FEE',222)}}</p>
    <form action="{{route('oc.transaction.approve')}}" method="POST">
        @method('PUT')
        <label for="debt">Debt:</label>
        @if ($errors->has('debt'))
            <span class="help-block"><strong style="color: red;">{{ $errors->first('debt') }}</strong></span>
        @endif
        <input id="debt" name="debt" type="text"><br>
        <input id="user" name="user" type="hidden" value="{{$user->id}}">
        @csrf <br>
        <input class="btn btn-success" type="submit" value="Approve">
        <a class="btn btn-danger" href="{{route('oc.cashflow.bank')}}">Cancel</a>
    </form>
@endsection