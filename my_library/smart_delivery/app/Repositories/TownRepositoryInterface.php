<?php

namespace App\Repositories;

use App\Models\Town;

interface TownRepositoryInterface
{
    public function get();

    public function all();

    public function show(Town $town);

    public function store($data);

    public function update($town, $data);

    public function delete($town);

    public function changeStatus($town);
}
