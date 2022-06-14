<?php

namespace App\Http\Middleware;

use App\Settings;
use Closure;

class StoppedService
{

    public function handle($request, Closure $next,$moduleToTest)
    {
        $settings=Settings::where('slug','module')->first();
        $settingsSpecifc=json_decode($settings->settings_data,true)[$moduleToTest];
        if ($settingsSpecifc != null and $settingsSpecifc['value'] != 0)
        {
            return $next($request);
        }
        flash(getPhrase('not_allowed'), getPhrase('this_service_is_not_available_now'), 'error');
        return redirect()->back();
    }
}
