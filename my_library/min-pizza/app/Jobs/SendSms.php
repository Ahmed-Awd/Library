<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSms implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private string $message;
    private User $user;


    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }


    public function handle()
    {
        sendSms($this->message, $this->user->country_code . $this->user->phone);
    }
}
