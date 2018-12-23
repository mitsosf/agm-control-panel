<?php

namespace App\Listeners;

use App\Events\UserPaid;
use App\Mail\PaymentConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $path = env('APPLICATION_DEPLOYMENT_PATH_PUBLIC').'invoices/' . $user->esn_country . '/' . $invID . $user->name . $user->surname . 'Fee.pdf';
        $pdf->save($path);

        //Send invoice to participant

        Mail::to($user->email)->send(new PaymentConfirmation($user, $path));

        //Save the whole transaction to the database

        /*$invoice = new Invoice();
        $invoice->user_id = $user->id;
        $invoice->path = $path;
        $invoice->section = $user->section;
        $invoice->esn_country = $user->esn_country;
        $invoice->save();*/

    }
}
