<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepositConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Deposit payment confirmation')->
        from('noreply@agmthessaloniki.org', 'AGM Thessaloniki 2019 - Payment System')->
        view('mails.sendDepositConfirmation')->
        with([
            'user' => $this->user,
        ]);
    }
}
