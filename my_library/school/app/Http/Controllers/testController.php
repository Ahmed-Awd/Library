<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Console\Input\Input;

class testController extends Controller
{


    function postConfirm()
    {
        $servicio = Service::find(Input::get('service_id'));
        $driver = Driver::find(Input::get('driver_id'));
        if ($servicio) {
            if ($servicio->status_id == '6') {
                return Response::json(array('error' => '2'));
            }

            if ($servicio->driver_id == NULL && $servicio->status_id == '1') {
                $servicio->update([
                    'driver_id' => $driver->id,
                    'status_id' => '2',
                    'car_id' => $driver->car_id
                ]);

                $driver->update(['available' => '0']);
                $pushMessage = 'Tu servicio ha sido confirmado';
                $push = Push::make();

                if ($servicio->user->uuid == '1') {
                    return Response::json(array('error' => '0'));
                }

                if ($servicio->user->type == '1') {
                    $push->ios($servicio->user->uuid, $pushMessage, '1', 'honk.wav', 'Open', ['serviceId' => $servicio->id]);
                } else {
                    $push->android2($servicio->user->uuid, $pushMessage, '1', 'default', 'Open', ['serviceId' => $servicio->id]);
                }
                return Response::json(array('error' => '0'));
            } else {
                return Response::json(array('error' => '1'));
            }
        }
    }
}

