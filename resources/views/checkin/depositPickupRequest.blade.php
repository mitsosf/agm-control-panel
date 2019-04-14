@extends('layouts.checkin.master')

@section('content')
    <div class="container">
        <h4>You currently hold: <b style="color: green;font-size: 30px"><u>{{$cash}} €</u></b>(in cash)</h4>
        <div class="row">
            <h3>Create deposit pickup request:</h3>
            <div class="col-md-3">
                <form action="{{route('checkin.funds.createRequest')}}" method="POST">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input id="amount" name="amount" type="text" class="form-control" placeholder="Amount (max: {{$cash}})">
                    </div>
                    <input id="cash" name="cash" type="hidden" value="{{$cash}}">
                    @csrf
                    <input class="btn btn-success" type="submit" value="Create request">
                    <a class="btn btn-danger" href="{{route('checkin.funds')}}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection