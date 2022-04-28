<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurkeySms implements SmsServices
{

    public function sendSms($sentto, $text, $sms_lang, $report)
    {
         $api_key  = 'ee3dd083d1130e6047054681b9f26450';
         $title    = '8507013986';//'8507013986';
         $sentto = intval($sentto);
         return $sentto;

        $body = array('api_key' => $api_key, 'title' => $title, 'text' => $text,
        'sentto' => $sentto,'sms_lang' => $sms_lang,'report' => 1,'response_type' => 'json');
        $response = Http::post('https://www.turkeysms.com.tr/api/v3/gonder/add-content', $body);
        return $response;
    }
}
