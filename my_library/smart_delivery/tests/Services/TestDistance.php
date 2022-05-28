<?php

namespace Tests\Services;

use App\Services\GeoDistance;

class TestDistance implements GeoDistance
{
    private static $distance;

    public function calculate(array $firstPoint, array $secondPoint): float
    {
        return self::$distance ?? 1;
    }

    public function setDistance($distance)
    {
        self::$distance = $distance;
    }
}
