<?php

namespace App\Repositories;

use App\Models\Town;

class TownRepository implements TownRepositoryInterface
{
    private Town $town;

    public function __construct(Town $town)
    {
        $this->town = $town;
    }

    public function get()
    {
        return $this->town->where('is_active', 1)->get();
    }

    public function all()
    {
        return $this->town->all();
    }

    public function show(Town $town)
    {
        return $town;
    }

    public function store($data)
    {
        $this->town->create($data);
    }

    public function update($town, $data)
    {
        $town->update($data);
    }

    public function delete($town)
    {
        $town->delete();
    }

    public function changeStatus($town)
    {
        $town->is_active = !$town->is_active;
        $town->save();
    }
}
