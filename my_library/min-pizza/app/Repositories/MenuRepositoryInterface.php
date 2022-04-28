<?php

namespace App\Repositories;

interface MenuRepositoryInterface
{
    public function menu($restaurant, $token);
    public function categories($restaurant);
    public function createCategory($restaurant, $data);
    public function showCategory($category);
    public function updateCategory($category, $data);
    public function deleteCategory($category);
    public function items($category);
    public function allItems($restaurant);
    public function createItem($category, $data);
    public function showItem($item, $token);
    public function updateItem($item, $data);
    public function deleteItem($item);
    public function assignItem($item, $option);
    public function unAssignItem($item, $option);
    public function unAssignItemAll($item);
    public function changeAvailability($item): bool;
    public function favOrUnFavItem($item);
    public function updateRank($item, $rank);
}
