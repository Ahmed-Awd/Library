<?php

namespace App\Repositories;

interface DiscountCodeRepositoryInterface
{
    public function get($search, $order);
    public function show($discountCode);
    public function store($data);
    public function update($discountCode, $data);
    public function delete($discountCode);
    public function myCodes();
    public function getMyCode($discountCode);
}
