<?php

namespace App\Repositories;

interface TaxRepositoryInterface
{
    public function get();
    public function show($tax);
    public function store($data);
    public function update($tax, $data);
    public function delete($tax);
}
