<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;
    protected $user;
    protected $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token, $url = null)
    {
        $this->token = $token;
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->user->email)
            ->view('emails.mail_forgot_password')
            ->with([
                'url' => $this->url ? $this->url : url(sprintf(config('api.auth.reset_password.url'), $this->token)),
                'name' => $this->user->name,
                'timeOut' => config('api.auth.reset_password.token_timeout'),
            ]);
    }
}
