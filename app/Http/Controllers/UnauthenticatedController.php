<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Faker\Generator as Faker;

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

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
        cas()->setFixedServiceURL(route('cas.login'));
        cas()->authenticate();

        if (!cas()->isAuthenticated()) {
            return redirect(route('home'));
        }

        $userCount = User::where('username', cas()->user())->count();
        if ($userCount == 0) {
            $newUser = new User();
            $newUser->username = cas()->user();
            cas()->getAttributes();
            $newUser->name = cas()->getAttribute('first');
            $newUser->surname = cas()->getAttribute('last');
            $newUser->role_id = 1;
            $newUser->esn_country = cas()->getAttribute('country');
            $newUser->gender = cas()->getAttribute('gender');
            $newUser->section = cas()->getAttribute('section');
            $newUser->birthday = cas()->getAttribute('birthdate');
            $newUser->email = cas()->getAttribute('mail');
            $newUser->photo = cas()->getAttribute('picture');
            $newUser->facebook = cas()->getAttribute('social-facebook');
            $newUser->save();
            $user = $newUser;
        } else {
            $user = User::where('username', cas()->user())->first();
            cas()->getAttributes();
            $user->name = cas()->getAttribute('first');
            $user->surname = cas()->getAttribute('last');
            $user->esn_country = cas()->getAttribute('country');
            $user->gender = cas()->getAttribute('gender');
            $user->section = cas()->getAttribute('section');
            $user->birthday = cas()->getAttribute('birthdate');
            $user->email = cas()->getAttribute('mail');
            $user->photo = cas()->getAttribute('picture');
            $user->facebook = cas()->getAttribute('social-facebook');
            $user->update();
        }

        //End cas session and start local one
        session_destroy(); //Destroy CAS cookie
        Auth::login($user);//Log the user into Laravel (natively)
        $user->refreshErsStatus();
        $user->getLatestInvoiceNumberAndAddress();

        //Check if NR
        if (is_array(cas()->getAttribute('roles'))) {
            if (in_array('National.nationalRepresentative', cas()->getAttribute('roles'))) {
                $user->comments = "NR";
                $user->update();
            }
        }

        $role = $user->role->name;
        switch ($role) {
            case 'Participant':
                return redirect(route('participant.home'));

            case 'OC':
                return redirect(route('oc.home'));

            default:
                return redirect(route('home'));
        }


    }

    public function faker(Faker $faker){

        $users = User::all();

        foreach($users as $user){
            $user->name = $faker->firstName;
            $user->surname = $faker->lastName;
            $user->username = $faker->unique()->userName;
            $user->password = '$2y$12$VLO75p/uB.VDED5eE09grO86VvgiB1ByoLa03uk2PL..pPWxSPXeK'; //abc123
            $user->email = $faker->unique()->safeEmail;
            $user->facebook = "";
            $user->comments="";
            $user->rooming_comments="";
            $user->document = $faker->ssn;
            $user->esncard = $faker->swiftBicNumber;
            $user->phone = $faker->mobileNumber;
            $user->invoice_address = $faker->address;
            $user->photo = 'https://cdn4.iconfinder.com/data/icons/avatars-circle-2/72/146-512.png';
            $user->update();
        }

        return 'Success';
    }
}
