<?php


namespace App\Repositories;


use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function get();

    public function show(Category $category);

    public function delete($id);

    public function store($data);

    public function update($id, $data);

}
