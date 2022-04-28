<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleDistance implements GeoDistance
{
    public function calculate(array $firstPoint, array $secondPoint): float
    {
        $distance = 0;
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => implode(',', $firstPoint),
            'destinations' => implode(',', $secondPoint),
            'mode' => 'DRIVING',
            'sensor' => 'false',
            'key' => config('services.gmaps.key'),
        ]);

        try {
            $distance = $response->json()['rows'][0]['elements'][0]['distance']['value'] / 1000;
        } catch (Exception $exception) {
            Log::error('google distance response', [
                $response->json()
            ]);
        }

        return $distance;
    }
}
