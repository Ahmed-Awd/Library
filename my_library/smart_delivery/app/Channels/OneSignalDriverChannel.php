<?php

namespace App\Channels;

use Berkayk\OneSignal\OneSignalClient;
use NotificationChannels\OneSignal\OneSignalChannel;

class OneSignalDriverChannel extends OneSignalChannel
{
    public function __construct()
    {
        $oneSignal = new OneSignalClient(
            config('services.onesignaldriver.app_id'),
            config('services.onesignaldriver.rest_api_key'),
            null
        );
        parent::__construct($oneSignal);
    }
}
