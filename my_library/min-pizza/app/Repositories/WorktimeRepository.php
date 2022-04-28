<?php

namespace App\Repositories;

use App\Models\Worktime;
use App\Repositories\WorktimeRepositoryInterface;
use Carbon\Carbon;

class WorktimeRepository implements WorktimeRepositoryInterface
{
    private Worktime $worktime;

    public function __construct(Worktime $worktime)
    {
        $this->worktime = $worktime;
    }
    public function get($id)
    {
        return $this->worktime->with('day')->where('restaurant_id', $id)->get();
    }
    public function store($id, $data)
    {
        $this->worktime->insertOrIgnore([
                "day_id" => $data["day_id"],
                "open_from" => $data["open_from"],
                "open_to" => $data["open_to"],
                "takeaway" => $data["takeaway"],
                "delivery" => $data["delivery"],
                "status" => $data["status"],
                "restaurant_id" => $id,
                "created_at" =>  Carbon::now(),
        ]);
    }
    public function delete($id)
    {
        $this->worktime->where('restaurant_id', $id)->delete();
    }
}
