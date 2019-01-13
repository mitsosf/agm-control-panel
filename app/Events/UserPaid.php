<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserPaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public  $user, $transaction_id;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param $transaction_id
     */
    public function __construct(User $user, $transaction_id)
    {
        $this->user = $user;
        $this->transaction_id = $transaction_id;
    }
}
