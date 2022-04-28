<?php

namespace App\Repositories;

interface LevelRepositoryInterface
{
    public function get();

    public function show($level);

    public function delete($level);

    public function store($data);

    public function update($level, $data);

    public function fetchLevels($program,$track);

    public function setLevels($program,$track,$levels);
}
