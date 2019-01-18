<?php

namespace App\Listeners;

use App\Events\UserPaidDeposit;
use App\Mail\DepositConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDepositMailConfirmation implements ShouldQueue
{
    public $queue = 'default';

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
     * @param  UserPaidDeposit  $event
     * @return void
     */
    public function handle(UserPaidDeposit $event)
    {
        $user = $event->user;

        //Send invoice to participant
        Mail::to($user->email)->send(new DepositConfirmation($user));
    }

    public function failed(UserPaidDeposit $event, $exception)
    {
        $user = $event->user;

        $message = 'Deposit for User: ' . $user->id . '. ' . $user->name . ' ' . $user->surname
            . '\"\n\"Exception: '. $exception;

        Log::channel('slack')->alert($message);
    }
}
