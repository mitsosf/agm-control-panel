<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnauthenticatedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function login()
    {
        cas()->authenticate();

        $userCount = User::where('username', cas()->user())->count();
        if ($userCount == 0) {
            $newUser = new User();
            $newUser->username = cas()->user();
            cas()->getAttributes();
            $newUser->name = cas()->getAttribute('first');
            $newUser->surname = cas()->getAttribute('last');
            $newUser->esn_country= cas()->getAttribute('country');
            $newUser->gender = cas()->getAttribute('gender');
            $newUser->section = cas()->getAttribute('section');
            $newUser->birthday= cas()->getAttribute('birthdate');
            $newUser->email = cas()->getAttribute('mail');
            $newUser->photo = cas()->getAttribute('picture');
            $newUser->facebook = cas()->getAttribute('social-facebook');
            $newUser->save();
            $user = $newUser;
        } else {
            $user = User::where('username', cas()->user())->first();
        }

        //End cas session and start local one
        session_destroy(); //Destroy CAS cookie
        Auth::login($user);//Log the user into Laravel (natively)

        $role = $user->role_id;
        switch ($role) {
            case "1":
                return redirect(route('participant.home'));

            case "2":
                return redirect(route('oc.home'));

            default:
                return redirect(route('home'));
        }



    }
}
