<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Services\SmsServices;

class SendSms implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected User $user;
    private $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $message)
    {
        //
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $res = resolve(SmsServices::class)
            ->SendSms("00" . $this->user->country_code . $this->user->phone, $this->message, 2, 1);
        return $res;
    }
}
