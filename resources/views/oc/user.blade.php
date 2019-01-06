@extends('layouts.oc.master')

@section('content')
    <h2>User: {{$user->name.' '.$user->surname}}</h2>
    <div class="container">
        <a href="{{$user->photo}}" target="_blank"><img src="{{$user->photo}}" alt="User photo" width="10%"></a>
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>ID</td>
                        <td>{{$user->id}}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <td>Surname:</td>
                        <td>{{$user->surname}}</td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>{{$user->role->name}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><a href="https://accounts.esn.org/user/{{str_replace('.', '', $user->username)}}" target="_blank">{{$user->username}}</a></td>
                    </tr>
                    <tr>
                        <td>Section</td>
                        <td>{{$user->section}}</td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td>{{$user->esn_country}}</td>
                    </tr>
                    <tr>
                        <td>ESNcard</td>
                        <td>{{$user->esncard}}</td>
                    </tr>
                    <tr>
                        <td>ID Document</td>
                        <td>{{$user->document}}</td>
                    </tr>
                    <tr>
                        <td>Date of birth</td>
                        <td>{{$user->birthday}}</td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        @if($user->gender === "M")
                            <td>Male <i class="fa fa-mars"></i></td>
                        @elseif($user->gender === "F")
                            <td>Female <i class="fa fa-venus"></i></td>
                        @else
                            <td><i class="fa fa-transgender"></i></td>
                        @endif
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td><a href="tel:{{$user->phone}}">{{$user->phone}}</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Spot Status</td>
                        <td>{{$user->spot_status}}</td>
                    </tr>
                    <tr>
                        <td>OC comments</td>
                        <td>{{$user->comments}}</td>
                    </tr>
                    <tr>
                        <td>Fee</td>
                        @if($user->fee != 0)
                            <td>{{$user->fee}}€ (<a href="{{route('oc.transaction.show',$user->transactions->where('type','fee')->first())}}">details</a>)</td>
                        @else
                            <td>{{$user->fee}}€</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Debt</td>
                        <td>{{$user->debt}}€</td>
                    </tr>
                    <tr>
                        <td>Roming</td>{{--TODO insert column actual into rooms table--}}
                        @if($user->rooming == 0)
                            <td>No</td>
                        @else
                            <td>{{$user->room->actual}}</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Rooming Comments</td>
                        <td>{{$user->rooming_comments}}</td>
                    </tr>
                    <tr>
                        <td>Checked-in</td>
                        @if($user->checkin == 1)
                            <td >Yes</td>
                        @else
                            <td>No</td>
                        @endif
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <a class="btn btn-danger" href="{{route('oc.approved')}}">Back</a>
    </div>
@endsection