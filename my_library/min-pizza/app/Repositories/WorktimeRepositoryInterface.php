<?php

namespace App\Repositories;

interface WorktimeRepositoryInterface
{
    public function get($id);
    public function store($id, $data);
    public function delete($id);
}
