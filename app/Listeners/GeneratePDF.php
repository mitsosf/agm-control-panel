<?php

namespace App\Listeners;

use App\Events\UserPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneratePDF implements ShouldQueue
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
     * @param  UserPaid $event
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
        $pdf->save($path);

    }
}
