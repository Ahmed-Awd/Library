<?php

namespace App\Repositories;

use App\Models\Holiday;
use App\Services\GeoDistance;

class RestaurantHolidayRepository implements RestaurantHolidayRepositoryInterface
{
    private Holiday $holiday;

    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }

    public function get($id)
    {
        return $this->holiday->where('restaurant_id', $id)->get();
    }

    public function store($data)
    {
        $this->holiday->create($data);
    }

    public function show($id)
    {
        return $this->holiday->findOrFail($id);
    }

    public function update($id, $data)
    {
        $record = $this->holiday->findOrFail($id);
        $record->update($data);
    }

    public function delete($id)
    {
        $this->holiday->where('id', $id)->delete();
    }
}
