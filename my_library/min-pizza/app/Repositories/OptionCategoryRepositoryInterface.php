<?php

namespace App\Repositories;

interface OptionCategoryRepositoryInterface
{
    public function get();
    public function show($category);
    public function showWereIn($category);
    public function store($data);
    public function update($category, $data);
    public function delete($category);
}
