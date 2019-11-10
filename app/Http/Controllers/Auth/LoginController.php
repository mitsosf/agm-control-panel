<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    protected function redirectTo(){

        if (\Illuminate\Support\Facades\Auth::guest()) {
            return view('welcome');
        } else {
            $role = Auth::user()->role->name;
            switch ($role) {
                case "Participant":
                    return redirect(route('participant.home'));

                case "OC":
                    return redirect(route('oc.home'));

                case "Checkin":
                    return redirect(route('checkin.home'));

                case "Voting":
                    return redirect(route('voting.home'));

                default:
                    return redirect(route('home'));
            }
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
