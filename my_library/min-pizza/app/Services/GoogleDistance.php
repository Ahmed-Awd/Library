<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleDistance implements GeoDistance
{
    public function calculate(array $firstPoint, array $secondPoint): array
    {
        $result['distance'] = -1;
        $result['duration'] = 4;
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => implode(',', $firstPoint),
            'destinations' => implode(',', $secondPoint),
            'mode' => 'driving',
            'sensor' => 'false',
            'key' => 'AIzaSyB_-R-N4JMQQfUveMj6YAZPrHgbldFkTSg',
        ]);

        try {
            $result['distance'] = $response->json()['rows'][0]['elements'][0]['distance']['value'] / 1000;
            $result['duration'] = $response->json()['rows'][0]['elements'][0]['duration']['value'] / 60;
        } catch (Exception $exception) {
            Log::error('google distance response', [
                $response->json()
            ]);
        }
        // $result['distance']=0;
        // $result['duration']= 4;
        return  $result;
    }
}
