<?php

namespace App\Jobs;

use App\Mail\SendOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $users;
    public $verificationOtp;
    public function __construct($users, $verificationOtp)
        {
            $this->users = $users;
            $this->verificationOtp= $verificationOtp;
        }
    
        /**
         * Execute the job.
         */
        public function handle(): void
        {
           Mail::to($this->users->email)->send(new SendOtp($this->users, $this->verificationOtp));
        }
}
