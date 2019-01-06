@extends('layouts.oc.master')

@section('content')
    <h2>Transaction #{{$transaction->id}}</h2>
    <div class="container">
        <div class="row">
            <table class="table">
                <tbody>
                <tr>
                    <td>ID</td>
                    <td>{{$transaction->id}}</td>
                </tr>
                <tr>
                    <td>User</td>
                    <td><a href="{{route('oc.user.show',$transaction->user)}}">{{$transaction->user->name.' '.$transaction->user->surname}}</a></td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>{{$transaction->type}}</td>
                </tr>
                @if($transaction->type === "fee")
                    <tr>
                        <td>Source</td>
                        @if($transaction->comments === "bank")
                            <td>Bank</td>
                        @else
                            <td>Card</td>
                        @endif
                    </tr>
                @endif
                <tr>
                    <td>Amount</td>
                    <td>{{$transaction->amount}}</td>
                </tr>
                <tr>
                    <td>Approved</td>
                    @if($transaction->approved == 1)
                        <td style="color: green">Yes</td>
                    @else
                        <td style="color: red;">No</td>
                    @endif
                </tr>
                <tr>
                    <td>Proof</td>
                    @if(substr($transaction->proof,0,3) === "pmt" || $transaction->proof === null)
                        <td>{{$transaction->proof}}</td>
                    @else
                        {{--<td><iframe src="{{$transaction->proof}}"></iframe></td>--}}
                        <td><button type="button" id="bank_acc" data-toggle="modal" data-target="#exampleModal">
                            Show proof
                        </button></td>
                    @endif
                </tr>
                <tr>
                    <td>Created at:</td>
                    <td>{{$transaction->created_at}}</td>
                </tr>
                <tr>
                    <td>Updated at:</td>
                    <td>{{$transaction->updated_at}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <a class="btn btn-danger" href="{{route('oc.cashflow')}}">Back</a>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="width: 80%!important;">
            <div class="modal-content" style="height: 80%!important;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Bank details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="height: 720px!important;">
                    <iframe src="{{$transaction->proof}}" height="100%" width="100%" style="display: block; margin: auto"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        #bank_acc {
            background: none !important;
            border: none;
            padding: 0 !important;

            /*optional*/
            font-family: arial, sans-serif; /*input has OS specific font-family*/
            color: #069;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
@endsection