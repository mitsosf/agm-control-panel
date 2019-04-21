<?php

namespace App\Http\Controllers;

use App\User;
use App\VoteDelegation;
use App\VoteRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotingDistributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('voting');
    }

    public function index()
    {
        $rounds = VoteRound::all();

        return view('voting.return.home', compact('rounds'));
    }

    public function round($round_id)
    {
        $delegations = VoteDelegation::where('vote_round_id', $round_id)->get();
        $round = VoteRound::find($round_id);

        $devices = VoteDelegation::where('vote_round_id',$round_id)->get();
        $given= VoteDelegation::where('given',1)->where('vote_round_id',$round_id)->get();
        $devicesCount = $devices->count();
        $givenCount = $given->count();
        $ratio = floor(($givenCount/$devicesCount)*100);

        return view('voting.round', compact('delegations', 'round', 'ratio', 'givenCount', 'devicesCount'));
    }

    public function validation($delegation_id)
    {
        $delegation = VoteDelegation::find($delegation_id);
        $user = User::find($delegation->user_id);
        $round_id = $delegation->vote_round_id;

        $delegations = VoteDelegation::where('user_id',$user->id)->where('type', $delegation->type)->get();
        return view('voting.validation', compact('user', 'round_id', 'delegations', 'delegation', 'delegation_id'));
    }

    public function device($delegation_id)
    {
        $delegation = VoteDelegation::find($delegation_id);
        $user = User::find($delegation->user_id);
        $delegations = VoteDelegation::where('user_id', $user->id)->where('vote_round_id', $delegation->vote_round_id)->where('given', $delegation->given)->get();


        foreach ($delegations as $device) {
            if ($device->given == 0) {
                //Give device to user
                $device->given = 1;
                $device->update();

            } elseif ($device->given == 1) {
                if (Auth::user()->role_id != 2) {
                    return redirect(route('voting.home'));
                }
                //Take device from user
                $device->given = 0;
                $device->update();

            } else {
                //Something went wrong  in the db
                return redirect(route('voting.home'));
            }
        }
        return redirect(route('voting.round',$delegation->vote_round_id));
    }

    public function returnRound($round_id)
    {
        $delegations = VoteDelegation::where('vote_round_id', $round_id)->get();
        $round = VoteRound::find($round_id);

        $devices = VoteDelegation::where('vote_round_id',$round_id)->get();
        $returned= VoteDelegation::where('given',0)->where('vote_round_id',$round_id)->get();
        $devicesCount = $devices->count();
        $returnedCount = $returned->count();
        $ratio = floor(($returnedCount/$devicesCount)*100);

        return view('voting.return.round', compact('delegations', 'round', 'ratio', 'returnedCount', 'devicesCount'));
    }

    public function returnValidation($delegation_id)
    {
        $delegation = VoteDelegation::find($delegation_id);
        $user = User::find($delegation->user_id);
        $round_id = $delegation->vote_round_id;

        $delegations = VoteDelegation::where('user_id',$user->id)->where('type', $delegation->type)->get();
        return view('voting.return.validation', compact('user', 'round_id', 'delegations', 'delegation', 'delegation_id'));
    }

    public function returnDevice($delegation_id)
    {
        $delegation = VoteDelegation::find($delegation_id);
        $user = User::find($delegation->user_id);
        $delegations = VoteDelegation::where('user_id', $user->id)->where('vote_round_id', $delegation->vote_round_id)->where('given', $delegation->given)->get();


        foreach ($delegations as $device) {
            if ($device->given == 1) {
                //Take device from user
                $device->given = 0;
                $device->update();

            } elseif ($device->given == 0) {
                if (Auth::user()->role_id != 2) {
                    return redirect(route('voting.home'));
                }
                //Un-Take device from user
                $device->given = 1;
                $device->update();

            } else {
                //Something went wrong  in the db
                return redirect(route('voting.home'));
            }
        }
        return redirect(route('voting.return.round',$delegation->vote_round_id));
    }



    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
