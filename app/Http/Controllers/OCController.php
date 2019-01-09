<?php

namespace App\Http\Controllers;

use App\Events\UserPaid;
use App\Hotel;
use App\Room;
use App\Transaction;
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
        $approvedUsers = User::where('spot_status', 'approved')->count();

        //Funds stats
        $paidUsers = User::where('fee', '!=', '0')->get();
        $funds = 0;
        foreach ($paidUsers as $user) {
            $funds += $user->fee;
        }

        $paidUsersCount = $paidUsers->count();

        //Rooming stats
        //TODO CHANGE TO ROOMS
        $roomedUsers = User::where('rooming', '!=', 'No')->count();

        //Check-in stats
        $checkedInUsers = User::where('checkin', '!=', '0')->count();


        return view('oc.home', compact('totalUsers', 'approvedUsers', 'roomedUsers', 'funds', 'paidUsersCount', 'checkedInUsers'));
    }

    public function approved()
    {

        $users = User::where('spot_status', 'approved')->orWhere('spot_status', 'paid')->get();


        return view('oc.approved', compact('users'));
    }

    public function cashflow()
    {
        $transactions = Transaction::where('type', 'fee')->get();

        $transactions_count = $transactions->count();
        //Get income

        $card_income = 0;
        $card_count = 0;
        $cash_income = 0;
        $cash_count = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->type === 'fee') {
                if ($transaction->comments == 'bank') {
                    $cash_income += $transaction->amount;
                    $cash_count++;
                } else {
                    $card_income += $transaction->amount;
                    $card_count++;
                }
            }
        }

        $income = $cash_income + $card_income;

        $deposits = Transaction::where('type', 'deposit')->get();

        $deposit_count = $deposits->count();
        $deposit_amount = 0;
        foreach ($deposits as $deposit) {
            $deposit_amount += $deposit->amount;
        }


        return view('oc.cashflow', compact('transactions', 'income', 'cash_income', 'card_income', 'deposit_count', 'deposit_amount', 'transactions_count', 'cash_count', 'card_count'));
    }


    public function cashflowCard()
    {
        $transactions = Transaction::where('type', 'fee')->whereNull('comments')->get();

        $card_count = $transactions->count();

        $card_income = 0;
        foreach ($transactions as $transaction) {
            $card_income += $transaction->amount;
        }

        return view('oc.cashflowCard', compact('transactions', 'card_income', 'card_count'));
    }


    public function cashflowBank()
    {
        //Get pending bank transaction data
        $pending_transactions = Transaction::where('type', 'fee')->where('comments', 'bank')->where('approved',0)->get();

        $pending_cash_count = $pending_transactions->count();

        $pending_cash_income = 0;
        foreach ($pending_transactions as $transaction) {
            $pending_cash_income += $transaction->amount;
        }

        //Get confirmed bank transaction data
        $confirmed_transactions = Transaction::where('type', 'fee')->where('comments', 'bank')->where('approved',1)->get();

        $confirmed_cash_count = $confirmed_transactions->count();

        $confirmed_cash_income = 0;
        foreach ($confirmed_transactions as $transaction) {
            $confirmed_cash_income += $transaction->amount;
        }

        //Get Debt
        $debt_transactions = Transaction::where('type','debt')->where('approved', 0)->get();

        $debt_count = $debt_transactions->count();

        $debt_amount = 0;
        foreach ($debt_transactions as $transaction){
            $debt_amount += $transaction->amount;
        }


        return view('oc.cashflowBank', compact('pending_transactions', 'pending_cash_income', 'pending_cash_count','confirmed_transactions', 'confirmed_cash_income', 'confirmed_cash_count', 'debt_amount', 'debt_count'));
    }

    public function cashflowBankSync()
    {

        //TODO sync with ERS

        return redirect(route('oc.cashflow.bank'));
    }

    public function transaction(Transaction $transaction)
    {
        return view('oc.transaction', compact('transaction'));
    }

    public function approveTransaction(Transaction $transaction)
    {
        $transaction->approved = 1;
        $transaction->update();

        $user = $transaction->user;

        event(new UserPaid($user, $transaction));
        return redirect(route('oc.cashflow.bank'));
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();
        //TODO Reject on ERS
        return redirect(route('oc.cashflow.bank'));
    }

    public function user(User $user)
    {
        return view('oc.user', compact('user'));
    }

    public function crudHotels()
    {
        $hotels = Hotel::all();
        return view('oc.crud.hotels', compact('hotels'));
    }

    public function showEditHotel(Hotel $hotel)
    {
        //TODO Show form prefilled with hotel data
        return view('oc.crud.editHotel', compact('hotel'));
    }

    public function editHotel(Hotel $hotel)
    {
        //TODO Edit the hotel, update DB
        return redirect(route('oc.crud.hotels'));
    }

    public function deleteHotel(Hotel $hotel)
    {
        $hotel->delete();
        return redirect(route('oc.crud.hotels'));
    }

    public function crudRooms()
    {
        $rooms = Room::all();
        return view('oc.crud.rooms', compact('rooms'));
    }

    public function showEditRoom(Room $room)
    {
        //TODO Show form prefilled with room data
        return view('oc.crud.editRoom', compact('room'));
    }

    public function editRoom(Request $request)
    {
        //TODO Edit the room, update DB
        return redirect(route('oc.crud.rooms'));
    }

    public function deleteRoom($id)
    {
        Room::find($id)->delete();
        return redirect(route('oc.crud.rooms'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
