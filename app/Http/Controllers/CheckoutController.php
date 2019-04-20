<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkout');
    }

    public function index()
    {

        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            $tempResidents = User::where('checkin','1')->get();
            $checkedOutCount[$hotel->id]=0;
            $checkedInCount[$hotel->id]=0;
            $residents = array();
            foreach ($tempResidents as $resident){
                if ($resident->transactions->where('type','deposit')->where('comments','cash')->where('approved',0)->count()>0){
                    array_push($residents, $resident);
                    $checkedOutCount[$hotel->id]++;
                    $checkedInCount[$hotel->id]++;
                }else{
                    if ($resident->transactions->where('type','deposit')->where('comments','cash')->count()>0){
                        $checkedInCount++;
                    }
                }

            }

            $residents = User::whereHas('transactions', function ($query){
                $query->where('type', 'deposit')->where('comments','cash');
            })->get();

            $checkedOut = $residents->where('checkin', 0)->count();

            $checkedInCount[$hotel->id] = Transaction::where('type','deposit')->where('comments','cash')->get()->count();
        }

        return view('checkout.home', compact('hotels', 'checkedOut', 'checkedInCount'));
    }

    public function hotel(Hotel $hotel)
    {
        $residents = User::whereHas('transactions', function ($query){
            $query->where('type', 'deposit')->where('comments','cash');
        })->get();

        $checkedOut = $residents->where('checkin', 0);

        return view('checkout.hotel', compact('hotel', 'residents', 'checkedOut'));
    }

    public function validation(Hotel $hotel, User $user)
    {
        if (Auth::user()->role_id != 2 && $user->checkin == 1) {
            return redirect(route('checkin.hotel', $hotel));
        }

        $deposit = $user->getCashDeposit();
        $cash = 0;
        if (!is_null($deposit)){
            $cash = $deposit->amount;
        }

        return view('checkout.validation', compact('user', 'hotel', 'cash'));
    }

    public function checkout(Request $request)
    {
        $user = User::find($request['user']);
        $hotel = Hotel::find($request['hotel']);

        if ($user->checkin == 1) {
            //Uncheckout

            $checkout = Transaction::where('user_id', $user->id)->where('type', 'checkout')->first();

            if (!is_null($checkout)) {
                if ($checkout->approved == 0) {
                    $checkout->approved = 1;
                    $checkout->comments = Auth::user()->id; //ID of checkiner
                    $checkout->update();
                }
            } else {
                //Save checkout as transaction
                $checkout = new Transaction();
                $checkout->user_id = $user->id;
                $checkout->type = "checkout";
                $checkout->comments = Auth::user()->id; //ID of checkiner
                $checkout->amount = 50;
                $checkout->proof = $request['proof'];
                $checkout->approved = 1;
                $checkout->save();
            }

            $deposit = Transaction::where('user_id',$user->id)->where('type','deposit')->where('comments','cash')->where('approved',1)->first();
            $deposit->approved=0;
            $deposit->update();

            //Check user out
            $user->checkin = 0;
            $user->update();
            return redirect(route('checkout.hotel', $hotel));

        } elseif ($user->checkin == 0) {


            if (Auth::user()->role_id != 2) {
                return redirect(route('checkout.hotel', $hotel));
            }

            //Check user back in
            $user->checkin = 1;
            $user->update();

            $deposit = Transaction::where('user_id',$user->id)->where('type','deposit')->where('comments','cash')->where('approved',0)->first();
            $deposit->approved=1;
            $deposit->update();

            //Cancel check-in transaction
            $checkout = Transaction::where('user_id', $user->id)->where('type', 'checkout')->where('approved', 1)->first();
            $checkout->approved = 0;
            $checkout->comments = Auth::user()->id; //Register who performed the uncheckin action
            $checkout->update();

            return redirect(route('checkout.hotel', $hotel));

        } else {
            return redirect(route('checkout.hotel', $hotel));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
