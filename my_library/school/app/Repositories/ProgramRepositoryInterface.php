<?php

namespace App\Repositories;

interface ProgramRepositoryInterface
{
    public function get();

    public function show($program);

    public function delete($program);

    public function store($data);

    public function update($program, $data);
}
