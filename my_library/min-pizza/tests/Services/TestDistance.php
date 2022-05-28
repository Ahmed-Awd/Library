<?php

namespace Tests\Services;

use App\Services\GeoDistance;

class TestDistance implements GeoDistance
{
    private static $result = [
        "distance" => 1,
        "duration" => 1
      ];

    public function calculate(array $firstPoint, array $secondPoint): array
    {
        return self::$result ;
    }


    public function setDistance($result)
    {
        self::$result = $result;
    }
}
