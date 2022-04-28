<?php

namespace App\Services;

interface SmsServices
{
    public function sendSms($sentto, $text, $sms_lang, $report);
}
