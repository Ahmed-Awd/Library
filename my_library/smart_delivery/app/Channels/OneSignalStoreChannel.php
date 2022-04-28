<?php

namespace App\Channels;

use Berkayk\OneSignal\OneSignalClient;
use NotificationChannels\OneSignal\OneSignalChannel;

class OneSignalStoreChannel extends OneSignalChannel
{
    public function __construct()
    {
        $oneSignal = new OneSignalClient(
            config('services.onesignal.app_id'),
            config('services.onesignal.rest_api_key'),
            null
        );
        parent::__construct($oneSignal);
    }
}
