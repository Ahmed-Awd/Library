<?php

namespace App\Repositories;

interface BranchRepositoryInterface
{
    public function get();

    public function show($branch);

    public function delete($branch);

    public function store($data);

    public function update($branch, $data);
}
