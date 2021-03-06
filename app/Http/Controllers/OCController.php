<?php

namespace App\Http\Controllers;

use App\Events\UserPaid;
use App\Events\UserPaidDeposit;
use App\Exports\UsersRoomsExport;
use App\Hotel;
use App\Imports\EntriesImport;
use App\Room;
use App\Transaction;
use App\User;
use App\VoteDelegation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Everypay\Everypay;
use Everypay\Payment;
use Everypay\Token;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Maatwebsite\Excel\Facades\Excel;

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
        $paidUsers = User::where('spot_status', 'paid')->get();
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

    public function final()
    {
        $users = User::where('spot_status', 'paid')->with('room')->get();

        return view('oc.final', compact('users'));
    }

    public function approved()
    {
        $users = User::where('spot_status', 'approved')->orWhere('spot_status', 'paid')->get();

        return view('oc.approved', compact('users'));
    }

    public function approvedSync()
    {
        $response = Curl::to(env('ERS_APPLICATIONS_API_URL'))
            ->withHeader('Event-API-key: ' . env('ERS_API_KEY'))
            ->returnResponseObject()
            ->get();

        if ($response->status !== 200) {
            return 'Error while contacting ERS';
        }

        $applications_json = json_decode($response->content);
        $approved_users = array();

        foreach ($applications_json as $application) {
            if (isset($application->spot_status)) {
                if ($application->spot_status === "Not Paid " || $application->spot_status === "Paid " || $application->spot_status === "Granted ") { //Careful of the extra space after the word "Paid"!
                    array_push($approved_users, $application);
                }
            }
        }

        foreach ($approved_users as $application) {
            //Check if user already has an account
            $user = User::where('username', $application->cas_name)->first();
            if (is_null($user)) {
                //Create user if new
                $new_user = new User();
                $new_user->email = $application->email;
                $new_user->username = $application->cas_name;
                $new_user->role_id = 1;
                $new_user->name = $application->first_name;
                $new_user->surname = $application->last_name;
                $new_user->esn_country = $application->country;
                $new_user->document = $application->idnumber;
                $new_user->phone = isset($application->phone) ? $application->phone : '';
                $new_user->section = $application->section_name;
                $new_user->gender = $application->gender;
                $new_user->birthday = Carbon::createFromTimestamp($application->date_of_birth)->format("d/m/Y");
                $new_user->spot_status = "approved";
                $new_user->allergies = $application->allergies;
                $new_user->setCreatedAt(Carbon::now());
                $new_user->setUpdatedAt(Carbon::now());
                $new_user->save();
            } else {
                $user->document = $application->idnumber;
                $user->phone = isset($application->phone) ? $application->phone : '';
                $user->allergies = $application->allergies;
                if ($user->spot_status != "pending") {
                    $user->update();
                    continue;
                }
                $user->spot_status = "approved";
                $user->update();
            }
        }

        return redirect(route('oc.approved'));
    }

    public function namechanges()
    {

        $users = User::where('spot_status', 'granted')->get();
        $completed_namechanges = User::where('spot_status', 'namechange')->get();
        return view('oc.namechanges', compact('users', 'completed_namechanges'));
    }


    public function namechangesSync()
    {

        $response = Curl::to(env('ERS_APPLICATIONS_API_URL'))
            ->withHeader('Event-API-key: ' . env('ERS_API_KEY'))
            ->returnResponseObject()
            ->get();

        if ($response->status !== 200) {
            return 'Error while contacting ERS';
        }

        $applications_json = json_decode($response->content);
        $granted_users = array();

        foreach ($applications_json as $application) {
            if (isset($application->spot_status)) {
                if (strpos($application->spot_status, 'Granted')) { //Careful of the extra space after the word "Paid"!
                    array_push($granted_users, $application);
                }
            }
        }

        foreach ($granted_users as $application) {
            //Check if user already has an account
            $user = User::where('username', $application->cas_name)->first();
            if (is_null($user)) {
                //Create user if new
                $new_user = new User();
                $new_user->email = $application->email;
                $new_user->username = $application->cas_name;
                $new_user->role_id = 1;
                $new_user->name = $application->first_name;
                $new_user->surname = $application->last_name;
                $new_user->esn_country = $application->country;
                $new_user->document = $application->idnumber;
                $new_user->phone = isset($application->phone) ? $application->phone : '';
                $new_user->section = $application->section_name;
                $new_user->gender = $application->gender;
                $new_user->birthday = Carbon::createFromTimestamp($application->date_of_birth)->format("d/m/Y");
                $new_user->spot_status = "granted";
                $new_user->allergies = $application->allergies;
                $new_user->setCreatedAt(Carbon::now());
                $new_user->setUpdatedAt(Carbon::now());
                $new_user->save();
            } else {
                //Make sure hasn't already been namechanged
                if ($user->spot_status == "namechange") {
                    continue;
                }
                $user->document = $application->idnumber;
                $user->phone = isset($application->phone) ? $application->phone : '';
                $user->allergies = $application->allergies;
                $user->spot_status = "granted";
                $user->update();
            }
        }

        return redirect(route('oc.namechanges'));
    }

    public function namechangesMatchShow(User $user)
    {
        $matchable_users = User::where('spot_status', 'approved')->get();

        return view('oc.namechangesMatch', compact('user', 'matchable_users'));
    }

    public function namechangesMatch(Request $request)
    {

        $giver = User::find($request['giver']);
        $taker = User::find($request['taker']);

        //Check if giver owes muniez
        $debt = $giver->transactions->where('type', 'debt')->first();
        if (isset($debt)) {
            $new_debt = new Transaction();
            $new_debt->user()->associate($taker);
            $new_debt->type = 'debt';
            $new_debt->amount = $debt->amount;
            $new_debt->approved = false;
            $new_debt->save();

            $debt->delete();
        }

        //Change taker's user entry
        $taker->fee = $giver->fee;
        $taker->comments = 'Namechange - Taker - ' . $giver->id;
        $taker->spot_status = 'paid';
        $taker->fee_date = Carbon::now();
        $taker->update();

        //Create new user payment, reference who gave the spot in the comments
        event(new UserPaid($taker, 'namechange'));

        //TODO Cancel giver's invoice

        //Change giver's user entry
        $giver->comments = 'Namechange - Giver - ' . $taker->id;
        $giver->fee = 0;
        $giver->spot_status = 'namechange';
        $giver->update();

        //Cancel giver transaction
        $giver->transactions->where('type', 'fee')->first()->delete();

        return redirect(route('oc.namechanges'));
    }

    public function cancelled()
    {
        $users = User::where('spot_status', 'cancelled')->get();
        return view('oc.cancelled', compact('users'));
    }

    public function cancelledSync()
    {

        $response = Curl::to(env('ERS_APPLICATIONS_API_URL'))
            ->withHeader('Event-API-key: ' . env('ERS_API_KEY'))
            ->returnResponseObject()
            ->get();

        if ($response->status !== 200) {
            return 'Error while contacting ERS';
        }

        $applications_json = json_decode($response->content);
        $potential_cancelled_usernames = array();

        foreach ($applications_json as $application) {

            if (isset($application->spot_status)) {
                if ($application->spot_status == 'Not Paid Canceled ' || explode(' ', $application->spot_status)[0] == 'Refunded') { //Cancelled spelled with one L in ERS*/
                    if (!in_array($application->cas_name, $potential_cancelled_usernames)) {
                        array_push($potential_cancelled_usernames, $application->cas_name);
                    }
                }
            }
        }

        $potential_cancelled_user_occurrences = array();
        foreach ($potential_cancelled_usernames as $username) {
            foreach ($applications_json as $application) {
                if (isset($application->spot_status)) {
                    if ($application->cas_name == $username) {
                        if (isset($potential_cancelled_user_occurrences[$username])) {
                            $potential_cancelled_user_occurrences[$username]++;
                        } else {
                            $potential_cancelled_user_occurrences[$username] = 1;
                        }
                    }
                }
            }
        }

        $cancelled_users = array();
        foreach ($potential_cancelled_user_occurrences as $username => $times) {
            if ($times == 1) {
                array_push($cancelled_users, $username);
            } else {
                foreach ($applications_json as $application) {
                    if (isset($application->spot_status)) {
                        if ($application->cas_name == $username) {
                            if ($application->spot_status != 'Not Paid Canceled ' && !strpos($application->spot_status, 'Refunded')) {
                                continue 2;
                            } else {
                                array_push($cancelled_users, $username);
                            }
                        }
                    }
                }
            }
        }


        //TODO rerun the foreach loop to get details of those that are cancelled
        $cancelled_applications = array();
        foreach ($cancelled_users as $username) {
            foreach ($applications_json as $application) {
                if (isset($application->spot_status)) {
                    if ($application->cas_name == $username) {
                        array_push($cancelled_applications, $application);
                        continue 2;
                    }
                }
            }
        }

        foreach ($cancelled_applications as $application) {
            //Check if user already has an account
            $user = User::where('username', $application->cas_name)->first();
            if (is_null($user)) {
                //Create user if new
                $new_user = new User();
                $new_user->email = $application->email;
                $new_user->username = $application->cas_name;
                $new_user->role_id = 1;
                $new_user->name = $application->first_name;
                $new_user->surname = $application->last_name;
                $new_user->esn_country = $application->country;
                $new_user->document = $application->idnumber;
                $new_user->phone = isset($application->phone) ? $application->phone : '';
                $new_user->section = $application->section_name;
                $new_user->gender = $application->gender;
                $new_user->birthday = Carbon::createFromTimestamp($application->date_of_birth)->format("d/m/Y");
                $new_user->spot_status = "cancelled";
                $new_user->allergies = $application->allergies;
                $new_user->setCreatedAt(Carbon::now());
                $new_user->setUpdatedAt(Carbon::now());
                $new_user->save();
            } else {
                //Make sure hasn't already been namechanged
                if ($user->spot_status == "namechange") {
                    continue;
                }
                $user->spot_status = "cancelled";
                $user->update();
            }
        }

        return redirect(route('oc.cancelled'));
    }

    public
    function cashflow()
    {
        $transactions = Transaction::where('type', 'fee')->where('approved', '1')->orderBy('updated_at', 'desc')->get();

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

        $debt_transactions = Transaction::where('type', 'debt')->orderBy('created_at', 'desc')->get();
        $debt_count = $debt_transactions->count();
        $debt_amount = $debt_transactions->sum("amount");


        return view('oc.cashflow', compact('transactions', 'income', 'cash_income', 'card_income', 'deposit_count', 'deposit_amount', 'transactions_count', 'cash_count', 'card_count', 'debt_amount', 'debt_count'));
    }


    public
    function cashflowCard()
    {
        $transactions = Transaction::where('type', 'fee')->whereNull('comments')->orderBy('updated_at', 'desc')->get();

        $card_count = $transactions->count();

        $card_income = $transactions->sum('amount');

        return view('oc.cashflowCard', compact('transactions', 'card_income', 'card_count'));
    }


    public
    function cashflowBank()
    {
        //Get pending bank transaction data

        $pending_transactions = Transaction::where('type', 'fee')->where('comments', 'bank')->where('approved', 0)->orderBy('proof', 'asc')->get();

        $pending_cash_count = $pending_transactions->where('proof', '!=', 'No proof')->count();

        $pending_cash_income = $pending_transactions->where('proof', '!=', 'No proof')->sum('amount');

        //Get confirmed bank transaction data
        $confirmed_transactions = Transaction::where('type', 'fee')->where('comments', 'bank')->where('approved', 1)->orderBy('updated_at', 'desc')->get();

        $confirmed_cash_count = $confirmed_transactions->count();

        $confirmed_cash_income = $confirmed_transactions->sum('amount');

        //Get Debt
        $debt_transactions = Transaction::where('type', 'debt')->where('approved', 0)->orderBy('created_at', 'desc')->get();

        $debt_count = $debt_transactions->count();

        $debt_amount = $debt_transactions->sum('amount');

        return view('oc.cashflowBank', compact('pending_transactions', 'pending_cash_income', 'pending_cash_count', 'confirmed_transactions', 'confirmed_cash_income', 'confirmed_cash_count', 'debt_amount', 'debt_count', 'pending_users'));
    }

    public
    function cashflowDebts()
    {
        $debts = Transaction::where('type', 'debt')->where('approved', 0)->orderBy('updated_at', 'desc')->get();

        $debt_amount = 0;
        $debt_count = 0;
        foreach ($debts as $debt) {
            if ($debt->approved == 0) {
                $debt_amount += $debt->amount;
                $debt_count++;
            }
        }

        return view('oc.cashflowDebts', compact('debts', 'debt_amount', 'debt_count'));
    }

    public
    function cashflowDeposits()
    {
        $deposits = Transaction::where('type', 'deposit')->where('comments', 'card')->get();

        $deposit_amount = $deposits->sum('amount');
        $deposit_count = $deposits->count();


        $card_deposits = Transaction::where('type', 'deposit')->where('comments', 'card')->get();
        $cash_deposits = Transaction::where('type', 'deposit')->where('comments', 'cash')->get();
        return view('oc.cashflowDeposits', compact('deposits', 'deposit_amount', 'deposit_count', 'cash_deposits', 'card_deposits'));
    }

    public
    function acquireDeposit(Transaction $transaction)
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

    public
    function refundDeposit(Transaction $transaction)
    {

        //If transaction isn't a deposit
        if ($transaction->type !== 'deposit') {
            dd($transaction);
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

    public
    function cashflowBankSync()
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
            if (isset($application->spot_status)) {
                if ($application->spot_status === "Not Paid " || $application->spot_status === "Paid ") { //Careful of the extra space after the word "Paid"!
                    // If it is an array, there is no proof of payment uploaded and if paid, we don't want to see it as pending
                    array_push($bank_payments, $application);
                }
            }
        }

        foreach ($bank_payments as $application) {
            //Check if user already has an account
            $user = User::where('username', $application->cas_name)->first();
            if (is_null($user)) {
                //Create user if new
                $new_user = new User();
                $new_user->username = $application->cas_name;
                $new_user->name = $application->first_name;
                $new_user->surname = $application->last_name;
                $new_user->email = $application->email;
                $new_user->section = $application->section_name;
                $new_user->esn_country = $application->country;
                $new_user->role_id = 1;
                $new_user->spot_status = 'approved';
                $new_user->setCreatedAt(Carbon::now());
                $new_user->setUpdatedAt(Carbon::now());
                $new_user->save();
                $user = $new_user;
            } else {
                if ($user->spot_status == 'pending' || is_null($user->spot_status)) {
                    $user->spot_status = 'approved';
                    $user->update();
                }
            }

            //Check if transaction already exists for this user
            if ($user->transactions->isNotEmpty()) {
                $user_transactions = $user->transactions->where('type', 'fee');
                if ($user_transactions->count() > 0) {
                    $transaction = $user_transactions->first();
                    if (substr($transaction->proof, 0, 3) == "pmt") {
                        $transaction->update();
                        continue;
                    }
                    $transaction->proof = !is_array($application->proof_of_payment) ? $application->proof_of_payment : 'No proof';  //Get proof of payment from ERS
                    $transaction->update();
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
            $transaction->proof = !is_array($application->proof_of_payment) ? $application->proof_of_payment : 'No proof';  //Get proof of payment from ERS
            $transaction->save();
        }

        return redirect(route('oc.cashflow.bank'));
    }

    public
    function transaction(Transaction $transaction)
    {
        return view('oc.transaction', compact('transaction'));
    }

    public
    function approveTransactionShow(Transaction $transaction)
    {
        $user = $transaction->user;
        return view('oc.approveTransaction', compact('transaction', 'user'));
    }

    public
    function approveTransaction(Request $request)
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

    public
    function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();
        //TODO Reject on ERS
        return redirect(route('oc.cashflow.bank'));
    }

    public
    function editDebtShow(Transaction $transaction)
    {
        $user = $transaction->user;
        return view('oc.editDebt', compact('transaction', 'user'));
    }

    public
    function editDebt(Request $request)
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

    public
    function deleteDebt(Transaction $transaction)
    {
        $transaction->delete();

        return redirect(route('oc.cashflow.debts'));
    }

    public
    function user(User $user)
    {
        $debt = $user->transactions->where('type', 'debt')->sum('amount');

        return view('oc.user', compact('user', 'debt'));
    }

    public
    function editUserComments(Request $request)
    {
        $user = User::find($request['user']);
        $user->comments = $request['comments'];
        $user->update();

        return redirect(route('oc.user.show', $user));
    }

    public
    function showInvitations()
    {
        $invitations = User::where('spot_status', '!=', 'pending')->where('rooming_comments', 'like', 'invitation_pending%')->get();
        $sent_invitations = User::where('rooming_comments', 'like', 'sent%')->get();

        return view('oc.invitations', compact('invitations', 'sent_invitations'));
    }

    public
    function invitationsSync()
    {
        $response = Curl::to(env('ERS_APPLICATIONS_API_URL'))
            ->withHeader('Event-API-key: ' . env('ERS_API_KEY'))
            ->returnResponseObject()
            ->get();

        if ($response->status !== 200) {
            return 'Error while contacting ERS';
        }

        $applications_json = json_decode($response->content);
        $invited_users = array();


        foreach ($applications_json as $application) {
            if (isset($application->spot_status)) {
                if ($application->need_invitation == 1) {
                    $user = User::where('username', $application->cas_name)->first();
                    if (substr($user->rooming_comments, 0, 4) !== 'sent') {
                        array_push($invited_users, $application);
                    }
                }
            }
        }

        foreach ($invited_users as $application) {
            $user = User::where('username', $application->cas_name)->first();

            $user->rooming_comments = "invitation_pending" . '--' . $application->place_of_birth . '--' . $application->passport_expiry_date . '--' . $application->passport_issue_date . '--' . $application->invitation_letter_address;
            $user->update();
        }

        return redirect(route('oc.invitations.show'));
    }


    public
    function invitationSend(User $user)
    {
        $user->rooming_comments = 'sent--' . substr($user->rooming_comments, 20);
        $user->update();
        return redirect(route('oc.invitations.show'));
    }

    public function importRoomingShow()
    {
        return view('oc.imports.rooming');
    }

    public function importRooming(Request $request)
    {
        $tabs = Excel::toArray(new EntriesImport(), $request['data']);
        foreach ($tabs as $index => $tab) {
            $hotel = $index + 1;

            if (array_key_exists('room_code', $tab[0])) {
                $last_code = $tab[0]['room_code'];
                //Create first room
                $room = new Room();
                $room->hotel_id = $hotel;
                $room->beds = intval($tab[0]['size']);
                $room->code = $tab[0]['room_code'];
                $room->actual = intval($tab[0]['real_number']);
                $room->final = 1;
                $room->save();
            } else {
                break;
            }

            foreach ($tab as $key => $entry) {
                //Skip first entry
                if (is_null($entry['room_code'])) {
                    dd($entry);
                }

                if ($key == 0) {
                    continue;
                }

                if ($entry['room_code'] !== $last_code) {
                    //Create new room
                    $room = new Room();
                    $room->hotel_id = $hotel;
                    $room->beds = intval($entry['size']);
                    $room->code = $entry['room_code'];
                    $room->actual = intval($entry['real_number']);
                    $room->final = 1;
                    $room->save();

                    //Reset vars
                    $last_code = $entry['room_code'];
                }
            }

        }

        //Get users into rooms
        foreach ($tabs as $hotel) {
            foreach ($hotel as $entry) {
                if (array_key_exists('room_code', $entry)) {

                    $user = User::where('username', $entry['cas_username'])->first();
                    $room = Room::where('code', $entry['room_code'])->first();
                    if (!is_null($user) && !is_null($room)) {
                        $user->room_id = $room->id;
                        $user->rooming = 1;
                        $user->update();
                    }
                } else {
                    continue;
                }
            }
        }

        return redirect(route('oc.import.rooming.show'));
    }


    public
    function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }

    public function importEsncardShow()
    {
        return view('oc.imports.esncard');
    }

    public function importESNcard(Request $request)
    {
        $entries = Excel::toArray(new EntriesImport(), $request['data'])[0];

        foreach ($entries as $entry) {

            $user = User::where('email', $entry['email'])->first();
            if (!is_null($user)) {

                if (!is_null($entry['esncard'])) {
                    $user->esncard = $entry['esncard'];
                }

                $user->tshirt = $entry['tshirt'];
                if (strpos($user->spot_type, 'Section Delegate') || strpos($user->spot_type, 'National Representative')) {
                    $user->delegate = 1;
                }
                $user->update();

            }
        }
        return redirect(route('oc.import.esncard.show'));
    }

    public function checkinDepositRequests()
    {

        $oc_transactions = Transaction::where('type', 'oc')->get();

        return view('oc.depositRequests', compact('oc_transactions'));
    }

    public function checkinDepositRequestApprove(Transaction $transaction)
    {
        $transaction->approved = 1;
        $transaction->proof = Auth::user()->id;
        $transaction->update();

        return redirect(route('oc.checkin.depositRequests'));
    }

    public function checkinDepositRequestDelete(Transaction $transaction)
    {
        $transaction->delete();

        return redirect(route('oc.checkin.depositRequests'));
    }

    public function checkiners()
    {
        $checkiners = User::where('role_id', 3)->orWhere('role_id', '2')->get();

        $funds = [
            'cash' => array(),
            'deposited' => array(),
            'all' => array(),
        ];

        //Initialize array
        foreach ($checkiners as $checkiner) {
            $funds['cash'][$checkiner->id] = 0;
            $funds['deposited'][$checkiner->id] = 0;
            $funds['all'][$checkiner->id] = 0;
        }

        foreach ($checkiners as $checkiner) {
            //Calculate all funds received by checkiner
            $checkins = Transaction::where('type', 'checkin')->where('comments', $checkiner->id)->where('approved', 1)->get();
            foreach ($checkins as $transaction) {
                $funds['all'][$checkiner->id] += $transaction->amount;
            }

            $transactions = Transaction::where('user_id', $checkiner->id)->where('type', 'oc')->where('approved', 1)->get();
            foreach ($transactions as $transaction) {
                $funds['deposited'][$checkiner->id] += $transaction->amount;
            }
            $funds['cash'][$checkiner->id] = $funds['all'][$checkiner->id] - $funds['deposited'][$checkiner->id];

        }


        return view('oc.checkiners', compact('checkiners', 'funds'));
    }

    public function importVotingDelegationsShow()
    {
        return view('oc.imports.delegations');
    }

    public function importVotingDelegations(Request $request)
    {
        $users = Excel::toArray(new EntriesImport(), $request['data'])[0];
        //dd($users);

        foreach ($users as $user) {
            if ($user['delegate'] >= 1) {
                for ($i = 0; $i < $user['delegate']; $i++) {
                    //Create new VD for user
                    $delegation = new VoteDelegation();
                    $delegation->user_id = $user['id'];
                    $delegation->vote_round_id = 1; //1 is delegate, 2 is NR
                    $delegation->type = 'delegate';
                    $delegation->given = 0;
                    $delegation->save();
                }
                $participant = User::find($user['id']);
                $participant->delegate = 1;
                $participant->update();
            }
        }

        //Import NRs
        $nrs = collect();
        $users = User::all();
        foreach ($users as $user) {
            if (strpos($user->spot_type, 'National Representative')) {
                $nrs->push($user);
            }

        }

        foreach ($nrs as $nr) {
            $delegation = new VoteDelegation();
            $delegation->user_id = $nr->id;
            $delegation->vote_round_id = 2; //1 is delegate, 2 is NR
            $delegation->type = 'nr';
            $delegation->given = 0;
            $delegation->save();


            $nr->delegate = 1;
            $nr->update();
        }

        return redirect(route('oc.import.delegations.show'));
    }

    public function test()
    {
        return Excel::download(new UsersRoomsExport(), 'rooming.xlsx');


    }


}
