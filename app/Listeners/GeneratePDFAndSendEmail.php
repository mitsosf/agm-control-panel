<?php

namespace App\Listeners;

use App\Events\UserPaid;
use App\Invoice;
use App\Mail\PaymentConfirmation;
use App\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GeneratePDFAndSendEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserPaid  $event
     * @return void
     */
    public function handle(UserPaid $event)
    {
        //Generate PDF
        $user = $event->user;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('mails.paymentConfirmation', compact('user')));

        //Save invoice locally
        $invID = DB::table('invoices')->where('esn_country', $user->esn_country)->get()->count() + 1;
        $path = 'invoices/' . $user->esn_country . '/' . $invID . $user->name . $user->surname . 'Fee.pdf';
        $pdf->save(env('APPLICATION_DEPLOYMENT_PATH_PUBLIC').$path);

        //Send invoice to participant

        Mail::to($user->email)->send(new PaymentConfirmation($user, env('APPLICATION_DEPLOYMENT_PATH_PUBLIC').$path));

        //Save the whole transaction to the database

        //Create payment
        $payment = new Payment();
        $payment->user()->associate($user);
        $payment->amount = $user->fee;
        $payment->comments = null;
        $payment->approved = true;
        $payment->proof = '';
        $payment->save();

        //Create invoice and attach to payment
        $invoice = new Invoice();
        $invoice->path = $path;
        $invoice->esn_country = $user->esn_country;
        $invoice->section = $user->section;
        $invoice->payment()->associate($payment);
        $invoice->save();
    }
}
