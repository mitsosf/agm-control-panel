<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OCController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('oc');
    }

    public function index()
    {

        //User stats
        $totalUsers = User::all()->count(); //All that have ever logged in
        $approvedUsers = User::where('spot_status','approved')->count();

        //Funds stats
        $paidUsers = User::where('fee','!=', '0')->get();
        $funds = 0;
        foreach ($paidUsers as $user){
            $funds+= $user->fee;
        }

        $paidUsersCount = $paidUsers->count();

        //Rooming stats
        //TODO CHANGE TO ROOMS
        $roomedUsers = User::where('rooming','!=','No')->count();

        //Check-in stats
        $checkedInUsers = User::where('checkin','!=','0')->count();




        return view('oc.home', compact('totalUsers', 'approvedUsers', 'roomedUsers', 'funds', 'paidUsersCount','checkedInUsers'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
