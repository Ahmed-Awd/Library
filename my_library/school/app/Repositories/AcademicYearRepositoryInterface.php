<?php


namespace App\Repositories;


use App\Models\Academic;

interface AcademicYearRepositoryInterface
{
    public function get();

    public function show(Academic $academic);

    public function store($data);

    public function update(Academic $academic, $data);
}
