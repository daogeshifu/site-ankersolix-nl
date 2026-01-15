<?php

// app/Mail/ActivateAccount.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\User\User;
use Illuminate\Queue\SerializesModels;

class ActivateAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $activationToken;

    public function __construct(User $user, string $activationToken)
    {
        $this->user = $user;
        $this->activationToken = $activationToken;
    }

    public function build()
    {
        return $this->subject('Activate Your Account')
            ->view('bak.email-templates.activate-account')
            ->with([
                'user' => $this->user,
                'token' => $this->activationToken,
            ]);
    }
}