<?php

namespace App\Services;

interface GeoDistance
{
    public function calculate(array $firstPoint, array $secondPoint): array;
}
