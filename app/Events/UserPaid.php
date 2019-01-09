<?php

namespace App\Events;

use App\Transaction;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserPaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public  $user, $transaction;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Transaction $transaction
     */
    public function __construct(User $user, Transaction $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }
}
