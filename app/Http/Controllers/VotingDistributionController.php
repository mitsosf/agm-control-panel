<?php

namespace App\Http\Controllers;

use App\User;
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
        $delegates = User::where('delegate', '!=', 0)->get();

        return view('voting.home', compact('delegates'));
    }

    public function validation(User $user){
        return view('voting.validation', compact('user'));
    }

    public function device(User $user)
    {
        if ($user->delegate == 1) {
            //Give device to user
            $user->delegate = 2;
            $user->update();

            return redirect(route('voting.home'));
        } elseif ($user->delegate == 2) {
            //Get device from user
            $user->delegate = 1;
            $user->update();

            return redirect(route('voting.home'));
        } else {
            //Something went wrong  in the db
            return redirect(route('voting.home'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
