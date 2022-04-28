<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplayToFeedbackMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $record;


    public function __construct($record)
    {
        $this->record = $record;
    }


    public function build()
    {
        return $this->subject($this->record->title)
            ->with(['message' => $this])
            ->view('emails.feedback');
    }
}
