<?php

namespace App\Listeners;

use App\Events\UserPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendEmail
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
        Log::info('Send email', ['user' => $event->user]);
    }
}
