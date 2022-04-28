<?php

namespace App\Repositories;

interface ClassRepositoryInterface
{
    public function get();

    public function show($class, $withCourse);

    public function delete($class);

    public function store($data);

    public function update($class, $data);
}
