<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Mail\PaymentConfirmation;
use Everypay\Everypay;
use Everypay\Payment;
use Everypay\Token;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;
use function MongoDB\BSON\toJSON;

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
        return view('participants.home', compact('user', 'error'));
    }

    public function payment(){
        $user = Auth::user();
        $error = null;

        return view('participants.payment', compact('user', 'error'));
    }


    public function validateCard()
    {
        //Set up the private key
        Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));

        //Get token from submission
        $token = $_POST['everypayToken'];

        //Check if card is not Visa, MasterCard or Maestro
        $token_details = Token::retrieve($token);
        $type = $token_details->card->type;
        if ($type !== 'Visa' && $type !== 'MasterCard' && $type !== 'Maestro') { //Only accept Visa, MasterCard & Maestro
            $error = 'Your card issuer is unsupported, please use either a Visa, MasterCard or Maestro';
            $user = Auth::user();
            return view('participants.payment', compact('error', 'user'));
        }
        Session::put('token', $token);
        return redirect(route('participant.charge'));
    }


    public function charge()
    {
        //Set up the private key
        Everypay::setApiKey(env('EVERYPAYSECRETKEY'));

        $token = Session::get('token');

        //Charge card

        $user = Auth::user();

        //Format desc
        $description = $user->id . "." . $user->name . " " . $user->surname . "--" . $user->esn_country . "/" . $user->section;

        $payment = Payment::create(array(
            "amount" => 22000, //Amount in cents
            "currency" => "eur", //Currency
            "token" => $token,
            "description" => $description
        ));

        Session::forget('token');
        //TODO Check if transaction is correct
        //TODO Store in transactions table

        //TODO Generate proof of payment & send email (queue)

        /*//Generate invoice
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('mails.paymentConfirmation', compact('user')));

        //Save invoice locally
        $invID = $user->esn_country . (DB::table('invoices')->where('esn_country', $user->esn_country)->get()->count() + 1);
        $path = 'invoices/' . $user->esn_country . '/' . $invID . $user->name . $user->surname . 'Fee.pdf';
        $pdf->save($path);

        //Send invoice to participant
        //TODO enable emails
        //Mail::to($user->email)->send(new PaymentConfirmation($user, $path));

        //Save the whole transaction to the database

        $invoice = new Invoice();
        $invoice->user_id = $user->id;
        $invoice->path = $path;
        $invoice->section = $user->section;
        $invoice->esn_country = $user->esn_country;
        $invoice->save();*/

        //TODO Update ERS status
        return redirect(env('ERS_EVENT_REDIRECT_URL'));
    }
    //TODO test deposits by card

    public function parseToken()
    {
        //Set up the private key
        Everypay::setApiKey(env('EVERYPAY_SECRET_KEY'));

        //Get token from submission
        $token = $_POST['everypayToken'];

        //Check if card is not Visa, MasterCard or Maestro
        $token_details = Token::retrieve($token);
        $type = $token_details->card->type;
        if ($type !== 'Visa' && $type !== 'MasterCard' && $type !== 'Maestro') { //Only accept Visa, MasterCard & Maestro
            $error = 'Your card issuer is unsupported, please use either a Visa, MasterCard or Maestro';
            $user = Auth::user();
            return view('participants.home', compact('error', 'user'));
        }
        Session::put('token', $token);
        return redirect(route('participant.deposit'));
    }

    public function deposit()
    {
        //Set up the private key
        Everypay::setApiKey(env('EVERYPAYSECRETKEY'));

        $token = Session::get('token');

        //Charge card

        $user = Auth::user();

        //Format desc
        $description = $user->id . "." . $user->name . " " . $user->surname . "--" . $user->esn_country . "/" . $user->section;

        $payment = Payment::create(array(
            "amount" => 22000, //Amount in cents
            "currency" => "eur", //Currency
            "token" => $token,
            "description" => $description,
            "capture" => 0
        ));

        Session::forget('token');
        return view('participants.test', compact('payment'));
    }

    public function test(){






        return view('test', compact('response','part', 'invoice'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('home'));
    }
}
