<?php

namespace App\Http\Controllers;

use App\Events\UserPaid;
use App\Events\UserPaidDeposit;
use App\Hotel;
use App\Room;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Everypay\Everypay;
use Everypay\Payment;
use Everypay\Token;
use Ixudra\Curl\Facades\Curl;

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

        $card_income = $transactions->sum('amount');

        return view('oc.cashflowCard', compact('transactions', 'card_income', 'card_count'));
    }


    public function cashflowBank()
    {
        //Get pending bank transaction data

        $pending_transactions = Transaction::where('type', 'fee')->where('comments', 'bank')->where('approved', 0)->get();

        $pending_cash_count = $pending_transactions->count();

        $pending_cash_income = $pending_transactions->sum('amount');

        //Get confirmed bank transaction data
        $confirmed_transactions = Transaction::where('type', 'fee')->where('comments', 'bank')->where('approved', 1)->get();

        $confirmed_cash_count = $confirmed_transactions->count();

        $confirmed_cash_income = $confirmed_transactions->sum('amount');

        //Get Debt
        $debt_transactions = Transaction::where('type', 'debt')->where('approved', 0)->get();

        $debt_count = $debt_transactions->count();

        $debt_amount = $debt_transactions->sum('amount');

        return view('oc.cashflowBank', compact('pending_transactions', 'pending_cash_income', 'pending_cash_count', 'confirmed_transactions', 'confirmed_cash_income', 'confirmed_cash_count', 'debt_amount', 'debt_count', 'pending_users'));
    }

    public function cashflowDebts()
    {
        $debts = Transaction::where('type', 'debt')->get();

        $debt_amount = $debts->sum('amount');
        $debt_count = $debts->count();

        return view('oc.cashflowDebts', compact('debts', 'debt_amount', 'debt_count'));
    }

    public function cashflowDeposits()
    {
        $deposits = Transaction::where('type', 'deposit')->get();

        $deposit_amount = $deposits->sum('amount');
        $deposit_count = $deposits->count();

        return view('oc.cashflowDeposits', compact('deposits', 'deposit_amount', 'deposit_count'));
    }

    public function acquireDeposit(Transaction $transaction)
    {

        //If transaction isn't a deposit
        if ($transaction->type !== 'deposit') {
            return redirect(route('oc.cashflow.deposits'));
        }

        Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));

        $payment = Payment::capture($transaction->proof);

        if (isset($payment->token)) { //If payment is successful

            $transaction->approved = 1;
            $transaction->comments = 'Acquired by' . Auth::user()->surname;
            $transaction->update();
            $transaction->delete();

            return redirect(route('oc.cashflow.deposits'));
        }
        return dd($payment);
    }

    public function refundDeposit(Transaction $transaction)
    {

        //If transaction isn't a deposit
        if ($transaction->type !== 'deposit') {
            return redirect(route('oc.cashflow.deposits'));
        }

        Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));

        $payment = Payment::refund($transaction->proof);

        if (isset($payment->token)) { //If payment is successful

            $transaction->delete();

            return redirect(route('oc.cashflow.deposits'));
        }

        return dd($payment);
    }

    public function cashflowBankSync()
    {

        $response = Curl::to(env('ERS_APPLICATIONS_API_URL'))
            ->withHeader('Event-API-key: ' . env('ERS_API_KEY'))
            ->returnResponseObject()
            ->get();

        if ($response->status !== 200) {
            return 'Error while contacting ERS';
        }

        $applications_json = json_decode($response->content);
        $bank_payments = array();

        foreach ($applications_json as $application) {
            if (!is_array($application->proof_of_payment) && $application->spot_status === "Not Paid ") { //Careful of the extra space after the word "Paid"!
                // If it is an array, there is no proof of payment uploaded and if paid, we don't want to see it as pending
                array_push($bank_payments, $application);
            }
        }

        foreach ($bank_payments as $application) {
            //Check if user already has an account
            $user = User::where('username', $application->cas_name)->first();
            if (is_null($user)) {
                //Create user if new
                $new_user = new User();
                $new_user->username = $application->cas_name;
                $new_user->role_id = 1;
                $new_user->setCreatedAt(Carbon::now());
                $new_user->setUpdatedAt(Carbon::now());
                $new_user->save();
                $user = $new_user;
            }

            //Check if transaction already exists for this user
            if ($user->transactions->isNotEmpty()) {
                if ($user->transactions->where('type', 'fee')->where('comments', 'bank')->count() > 0) {

                    continue;
                }
            }

            //If old user and no transaction, create unapproved bank transaction and associate with user
            $transaction = new Transaction();
            $transaction->user()->associate($user);
            $transaction->type = "fee";
            $transaction->amount = $application->price;
            $transaction->comments = "bank";
            $transaction->approved = 0;
            $transaction->proof = $application->proof_of_payment;  //Get proof of payment from ERS
            $transaction->save();
        }

        return redirect(route('oc.cashflow.bank'));
    }

    public function transaction(Transaction $transaction)
    {
        return view('oc.transaction', compact('transaction'));
    }

    public function approveTransactionShow(Transaction $transaction)
    {
        $user = $transaction->user;
        return view('oc.approveTransaction', compact('transaction', 'user'));
    }

    public function approveTransaction(Request $request)
    {

        //Validate request
        $this->validate($request, [
            'debt' => 'required|numeric',
            'transaction' => 'required'
        ]);

        $transaction = Transaction::find($request['transaction']);
        $user = $transaction->user;

        $transaction->approved = 1;
        $transaction->update();

        //Update user info
        $user->fee = $transaction->amount;
        $user->fee_date = Carbon::now();
        $user->spot_status = 'paid';
        $user->update();

        event(new UserPaid($user, null));

        //Check if we had any debt
        $debt = $request['debt'];
        if ($debt != '0') {
            //Save debt
            $debt = new Transaction();
            $debt->amount = $request['debt'];
            $debt->type = 'debt';
            $debt->user_id = $user->id;
            $debt->approved = 0;
            $debt->save();
        }

        return redirect(route('oc.cashflow.bank'));
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();
        //TODO Reject on ERS
        return redirect(route('oc.cashflow.bank'));
    }

    public function editDebtShow(Transaction $transaction)
    {
        $user = $transaction->user;
        return view('oc.editDebt', compact('transaction', 'user'));
    }

    public function editDebt(Request $request)
    {
        //Validate request
        $this->validate($request, [
            'debt' => 'required|numeric',
            'transaction' => 'required'
        ]);

        $transaction = Transaction::find($request['transaction']);

        $transaction->amount = $request['debt'];
        $transaction->update();

        return redirect(route('oc.cashflow.debts'));
    }

    public function deleteDebt(Transaction $transaction)
    {
        $transaction->delete();

        return redirect(route('oc.cashflow.debts'));
    }

    public function user(User $user)
    {
        return view('oc.user', compact('user'));
    }

    public function editUserComments(Request $request)
    {
        $user = User::find($request['user']);
        $user->comments = $request['comments'];
        $user->update();

        return redirect(route('oc.user.show',$user));
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
