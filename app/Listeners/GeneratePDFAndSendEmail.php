<?php

namespace App\Listeners;

use App\Events\UserPaid;
use App\Invoice;
use App\Mail\PaymentConfirmation;
use App\Transaction;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GeneratePDFAndSendEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @param UserPaid $event
     */

    public $user, $transaction_id;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserPaid $event
     * @return void
     */
    public function handle(UserPaid $event)
    {
        //Generate PDF
        $user = $event->user;

        $pdf = App::make('dompdf.wrapper');
        $invID = Invoice::all()->count() + 1;
        $pdf->loadHTML(view('mails.paymentConfirmation', compact('user', 'invID')));

        //Save invoice locally
        $path = 'invoices/' . $invID . $user->name . $user->surname . $user->esn_country . 'Fee.pdf';
        $pdf->save(env('APPLICATION_DEPLOYMENT_PATH_PUBLIC') . $path);

        //Save the whole transaction to the database
        $token = $event->token;

        if (!is_null($token)) { //If token is not null, we have a new card transaction
            //Create transaction
            $transaction = new Transaction();
            $transaction->user()->associate($user);
            $transaction->amount = $user->fee;
            $transaction->comments = null;
            $transaction->approved = true;
            $transaction->proof = $token;
            $transaction->save();
        }else{ //If we have an existing transaction
            $transaction = $user->transactions->where('comments','bank')->first();
        }

        //Create invoice and attach to transaction
        $invoice = new Invoice();
        $invoice->path = $path;
        $invoice->esn_country = $user->esn_country;
        $invoice->section = $user->section;
        $invoice->transaction()->associate($transaction);
        $invoice->save();


        //Send invoice to participant
        Mail::to($user->email)->send(new PaymentConfirmation($user, env('APPLICATION_DEPLOYMENT_PATH_PUBLIC') . $path));

        //TODO update ERS status
    }

    public function failed(UserPaid $event, $exception)
    {
        $user = $event->user;
        $token = $event->token;

        $message = "'User: ' . $user->id . '. ' . $user->name . ' ' . $user->surname 
            . '\"\n\"Token: ' . $token
            . '\"\n\"Exception: '. $exception";

        Log::channel('slack')->alert($message);
    }
}
