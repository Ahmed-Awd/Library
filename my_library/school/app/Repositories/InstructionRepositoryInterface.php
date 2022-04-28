<?php

namespace App\Repositories;

interface InstructionRepositoryInterface
{
    public function get();

    public function show($instruction);

    public function delete($instruction);

    public function store($data);

    public function update($instruction, $data);
}
