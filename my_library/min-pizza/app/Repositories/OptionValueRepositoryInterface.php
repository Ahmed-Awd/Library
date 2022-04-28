<?php

namespace App\Repositories;

interface OptionValueRepositoryInterface
{
    public function get($category);
    public function show($value);
    public function store($data);
    public function update($value, $data);
    public function delete($value);
}
