<?php

namespace App\Repositories;

interface TimingSetRepositoryInterface
{
    public function get();

    public function show($timingSet);

    public function delete($timingSet);

    public function store($data);

    public function update($timingSet, $data);
}
