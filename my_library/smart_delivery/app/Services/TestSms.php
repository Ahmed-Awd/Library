<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestSms implements SmsServices
{

    public function sendSms($sentto, $text, $sms_lang, $report)
    {
        return ;
    }
}
