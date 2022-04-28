<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

class ResetPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $details;
    public $code;
    public $password_reset;
    public $reset_body;


    public function __construct($details, $code)
    {
        $this->details = $details;
        $this->password_reset = Lang::get('messages.auth.password_reset');
        $this->reset_body = Lang::get('messages.auth.reset_body');
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('reset your password')
            ->with(['message' => $this])
            ->view('emails.reset');
    }
}
