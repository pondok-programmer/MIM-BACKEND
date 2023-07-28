<?php

namespace App\Jobs;

use App\Mail\SendEmailVerify;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendEmailVerifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $users;
    public $verification;
        /**
         * Create a new job instance.
         */
        public function __construct($users, $verification)
        {
            $this->users = $users;
            $this->verification= $verification;
        }
    
        /**
         * Execute the job.
         */
        public function handle(): void
        {
           Mail::to($this->users->email)->send(new SendEmailVerify($this->users, $this->verification));
        }
}
