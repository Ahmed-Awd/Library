<?php

namespace App\Repositories;

interface DriverTempRepositoryInterface
{

    public function get();
    public function store($data);
    public function show($driver);
    public function delete($driver);
}
