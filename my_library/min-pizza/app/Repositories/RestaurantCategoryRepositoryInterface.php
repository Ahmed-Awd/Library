<?php

namespace App\Repositories;

interface RestaurantCategoryRepositoryInterface
{
    public function store($id, $category_id);
    public function delete($id);
}
