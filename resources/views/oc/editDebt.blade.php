@extends('layouts.oc.master')

@section('content')
    <div style="text-align: center">
        <h3>Edit debt:</h3>
        <a href="{{$user->photo}}" target="_blank"><img src="{{$user->photo}}" alt="User photo" width="10%"></a>
        <h4>Name: <a href="{{route('oc.user.show',$user)}}">{{$user->name. " ". $user->surname}}</a></h4>
        <h4>Country: {{$user->esn_country}}</h4>
        <h4>Section: {{$user->section}}</h4>
        <h4>Debt: {{$transaction->amount}} €</h4>
        <form action="{{route('oc.debt.edit')}}" method="POST">
            @method('PUT')
            <label for="debt">Debt:</label>
            @if ($errors->has('debt'))
                <span class="help-block"><strong style="color: red;">{{ $errors->first('debt') }}</strong></span>
            @endif
            <input id="debt" name="debt" type="text" value="{{$transaction->amount}}"><br>
            <input id="transaction" name="transaction" type="hidden" value="{{$transaction->id}}">
            @csrf <br>
            <input class="btn btn-success" type="submit" value="Submit">
            <a class="btn btn-danger" href="{{route('oc.cashflow.debts')}}">Cancel</a>
        </form>
    </div>
@endsection