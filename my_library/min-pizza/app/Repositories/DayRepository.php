<?php

namespace App\Repositories;

use App\Models\Day;
use App\Repositories\DayRepositoryInterface;

class DayRepository implements DayRepositoryInterface
{
    private Day $day;

    public function __construct(Day $day)
    {
        $this->day = $day;
    }
    public function get()
    {
        return $this->day->all();
    }
}
