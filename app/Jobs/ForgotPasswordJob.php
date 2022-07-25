<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $token;
    public $user;
    public $platform;

    public function __construct($token, $user, $platform)
    {
        $this->token = $token;
        $this->user = $user;
        $this->platform = $platform;
    }

    /**
     * Execute the job.
     *
     * @return MailMessage
     */
    public function handle()
    {
        //send forgot password email
        $FE_URL = $this->platform == 'angular' ? env('ANGULAR_URL') : env('NUXT_URL');
        $url = $FE_URL . 'accounts/forgot_password/' . $this->token;
//        Log::channel('mail')->info("URL = $url");
        $user = $this->user;
        Mail::send('mails.forgot_password', ['url' => $url], function ($message) use ($user) {
            $message->from('quynb@kaopiz.com', 'Quynb');
            $message->to($user->email, $user->name)->subject('Reset Password');
            $message->subject('Reset Password');
        });
    }
}
