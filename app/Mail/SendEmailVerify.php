<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailVerify extends Mailable
{
    use Queueable, SerializesModels;
    public $users;
    public $verification;

    public function __construct($users, $verification)
    {
        $this->users = $users;
        $this->verification = $verification;
    }

    public function build()
    {
        return $this->view('VerifyEmail.VerifyEmail')
        ->with([
            'user' => $this->users,
            'verification' => $this->verification
        ]);
            
    }
}
