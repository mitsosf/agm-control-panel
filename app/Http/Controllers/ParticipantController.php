<?php

namespace App\Http\Controllers;

use App\Events\UserPaid;
use App\Events\UserPaidDeposit;
use App\Invoice;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Everypay\Everypay;
use Everypay\Payment;
use Everypay\Token;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ParticipantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('participant');
    }

    public function index()
    {
        $user = Auth::user();
        $error = null;

        $debt = $user->transactions->where('type', 'debt')->where('approved', 0)->first();

        $deposit_check = $user->transactions->where("type", "deposit")->count();

        return view('participants.home', compact('user', 'error', 'debt', 'deposit_check'));
    }

    public function payment()
    {
        if (!env('EVENT_PAYMENTS', 0)) {
            return redirect(route('participant.home'));
        }

        $user = Auth::user();
        $error = null;

        $transactions = $user->transactions->where('type', 'fee');

        $invoice = null;
        if ($transactions->count() > 0) {
            $invoice = $transactions->first()->invoice;
        }

        $bank_reference = $user->invoice_number !== "" ? $user->name . ' ' . $user->surname . ' - ' . $user->invoice_number . ' - AGM Thessaloniki 2019' : "DO NOT PAY";

        return view('participants.payment', compact('user', 'error', 'invoice', 'bank_reference'));
    }

    //TODO log ALL errors
    //TODO reassure the plembs that they have not been charged, if an error occurs
    public function validateCard()
    {
        if (!env('EVENT_PAYMENTS', 0)) {
            return redirect(route('participant.home'));
        }
        //Set up the private key
        Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));

        //Get token from submission
        $token = $_POST['everypayToken'];
        $user = Auth::user();
        $invoice = null;
        $bank_reference = $user->invoice_number !== "" ? $user->name . ' ' . $user->surname . ' - ' . $user->invoice_number . ' - AGM Thessaloniki 2019' : "DO NOT PAY";

        if (isset($token)) {
            //Check if card is not Visa, MasterCard or Maestro
            $token_details = Token::retrieve($token);
            if (isset($token_details->card)) {
                $type = $token_details->card->type;
                if ($type !== 'Visa' && $type !== 'MasterCard' && $type !== 'Maestro') { //Only accept Visa, MasterCard & Maestro
                    $error = 'Your card issuer is unsupported, please use either a Visa, MasterCard or Maestro';
                    return view('participants.payment', compact('user', 'error', 'invoice', 'bank_reference'));
                }
                Session::put('token', $token);
                //If all goes according to plan
                return redirect(route('participant.charge'));
            } else {
                //If we don't receive the token_details
                $error = "An error has occurred, please try again (Error 100)";
                return view('participants.payment', compact('user', 'error', 'invoice', 'bank_reference'));
            }
        }
        //If we don't receive a token
        $error = "An error has occurred, please try again (Error 101)";
        return view('participants.payment', compact('user', 'error', 'invoice', 'bank_reference'));
    }


    public function charge()
    {
        //Set up the private key
        Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));
        $user = Auth::user();
        $error = '';
        $invoice = null;
        $bank_reference = $user->invoice_number !== "" ? $user->name . ' ' . $user->surname . ' - ' . $user->invoice_number . ' - AGM Thessaloniki 2019' : "DO NOT PAY";

        //Charge card
        $token = Session::get('token');
        if (isset($token)) {

            //Format desc
            $description = $user->id . "." . $user->name . " " . $user->surname . "--" . $user->esn_country . "/" . $user->section;

            $payment = Payment::create(array(
                "amount" => 22200, //Amount in cents
                "currency" => "eur", //Currency
                "token" => $token,
                "description" => $description
            ));

            Session::forget('token');

            if (isset($payment->token)) {
                //TODO Check if transaction is correct

                //Update user info
                $user->fee = $payment->amount / 100;
                $user->fee_date = Carbon::now();
                $user->spot_status = 'paid';
                $user->update();

                //Generate PDF invoice, send it to the user and update DB
                event(new UserPaid($user, $payment->token));

                //If all goes well and user is charged
                Session::flash('paid_fee', 1);
                return redirect(route('participant.home'));
            } else {
                $error = "Your card issuer didn't approve the payment. If this problem persists, please try using a different card (Error 103)";
                return view('participants.payment', compact('user', 'error', 'invoice', 'bank_reference'));
            }
        } else {
            //If validation succeeds but charging fails
            $error = "An error has occurred, please try again (Error 102)";
            return view('participants.payment', compact('user', 'error', 'invoice', 'bank_reference'));
        }
    }

    public function deposit()
    {

        $user = Auth::user();
        $error = null;

        /* Check if user has already paid the deposit
         * Paid = 1
         * Not paid = 0
         * Something weird = Whatever
        */

        $deposit_check = $user->transactions->where("type", "deposit")->count();

        return view('participants.deposit', compact('user', 'error', 'deposit_check'));
    }

    public function parseToken()
    {
        try {
            //Set up the private key
            Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));

            //Get token from submission
            $token = $_POST['everypayToken'];
            $user = Auth::user();
            if (isset($token)) {
                //Check if card is not Visa, MasterCard or Maestro
                $token_details = Token::retrieve($token);
                if (isset($token_details->card)) {
                    $type = $token_details->card->type;
                    if ($type !== 'Visa' && $type !== 'MasterCard' && $type !== 'Maestro') { //Only accept Visa, MasterCard & Maestro
                        $error = 'Your card issuer is unsupported, please use either a Visa, MasterCard or Maestro';
                        $deposit_check = $user->transactions->where("type", "deposit")->count();
                        return view('participants.deposit', compact('error', 'user', 'deposit_check'));
                    }
                    //If all works
                    Session::put('token', $token);
                    return redirect(route('participant.deposit.charge'));
                } else {
                    //If we don't receive the token_details
                    $error = "An error has occurred, please try again (Error 100)";
                    $deposit_check = $user->transactions->where("type", "deposit")->count();
                    return view('participants.deposit', compact('user', 'error', 'deposit_check'));
                }
            }

            //If we don't receive a token
            $error = "An error has occurred, please try again (Error 101)";
            $deposit_check = $user->transactions->where("type", "deposit")->count();
            return view('participants.payment', compact('user', 'error', 'deposit_check'));
        } catch (\Exception $exception) {
            $user = Auth::user();

            $message = 'User: ' . $user->id . '. ' . $user->name . ' ' . $user->surname
                . '\"\n\"Token: ' . $token
                . '\"\n\"Exception: ' . $exception;

            Log::channel('slack')->alert($message);
            redirect(route('participant.deposit'));
        }
    }

    public function chargeDeposit()
    {
        try {
            //Set up the private key
            Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));
            $user = Auth::user();
            $error = '';

            //Pre-charge card
            $token = Session::get('token');
            if (isset($token)) {

                //Format desc
                $description = 'Deposit--' . $user->id . "." . $user->name . " " . $user->surname . "--" . $user->esn_country . "/" . $user->section;

                $payment = Payment::create(array(
                    "amount" => 5000, //Amount in cents
                    "currency" => "eur", //Currency
                    "token" => $token,
                    "description" => $description,
                    "capture" => 0  //Authorize card only
                ));
                Session::forget('token');

                if (isset($payment->token)) {
                    //TODO Check if transaction is correct

                    //Send mail to the user

                    //If all goes well and user is charged
                    //Save deposit to db
                    $deposit = new Transaction();
                    $deposit->type = 'deposit';
                    $deposit->amount = $payment->amount / 100;
                    $deposit->approved = 0;
                    $deposit->comments = 'card';
                    $deposit->proof = $payment->token;
                    $deposit->user()->associate($user);
                    $deposit->save();

                    event(new UserPaidDeposit($user));

                    //Display success message on homepage
                    Session::flash('paid_deposit', 1);
                    return redirect(route('participant.home'));
                } else {
                    $error = "Your card issuer didn't approve the payment. If this problem persists, please try using a different card (Error 103)";
                    $deposit_check = $user->transactions->where("type", "deposit")->count();
                    return view('participants.deposit', compact('user', 'error', 'deposit_check'));
                }
            } else {
                //If validation succeeds but pre-charging fails
                $error = "An error has occurred, please try again (Error 102)";
                $deposit_check = $user->transactions->where("type", "deposit")->count();
                return view('participants.deposit', compact('user', 'error', 'deposit_check'));
            }
        } catch (\Exception $exception) {
            $user = Auth::user();
            $token = $token;

            $message = 'User: ' . $user->id . '. ' . $user->name . ' ' . $user->surname
                . '\"\n\"Token: ' . $token
                . '\"\n\"Exception: ' . $exception;

            Log::channel('slack')->alert($message);
            redirect(route('participant.deposit'));
        }
    }

    public function delegation()
    {
        $user = Auth::user();
        if (substr($user->comments, 0, 2) !== "NR") {
            return redirect(route('participant.home'));
        }

        $participants = User::where('esn_country', $user->esn_country)->whereIn('spot_status', ['paid', 'approved'])->get();

        return view('participants.delegation', compact('participants'));
    }

    public function generateProof()
    {
        return Auth::user()->generateProof();
    }

    public function certificate(){
        $user = Auth::user();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('participants.certificate', compact('user')));
        return $pdf->stream($user->name." ".$user->surname." - AGM Thessaloniki Certificate of attendance.pdf");
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
