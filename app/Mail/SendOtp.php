<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $users;
    public $verificationOtp;

    public function __construct($users, $verificationOtp)
    {
        $this->users = $users;
        $this->verificationOtp = $verificationOtp;
    }

    public function build()
    {
        return $this->view('VerifyEmail.VerifyEmailMobile')
        ->with([
            'user' => $this->users,
            'verificationOtp' => $this->verificationOtp
        ]);
            
    }
}
