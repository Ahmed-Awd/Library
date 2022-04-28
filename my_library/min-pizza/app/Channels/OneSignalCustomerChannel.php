<?php

namespace App\Channels;

use Berkayk\OneSignal\OneSignalClient;
use NotificationChannels\OneSignal\OneSignalChannel;

class OneSignalCustomerChannel extends OneSignalChannel
{
    public function __construct()
    {
        $oneSignal = new OneSignalClient(
            config('services.onesignalcustomer.app_id'),
            config('services.onesignalcustomer.rest_api_key'),
            null
        );
        parent::__construct($oneSignal);
    }
}
