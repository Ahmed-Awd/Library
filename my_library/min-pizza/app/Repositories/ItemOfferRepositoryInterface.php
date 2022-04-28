<?php

namespace App\Repositories;

interface ItemOfferRepositoryInterface
{
    public function get($search, $order);
    public function show($itemOffer);
    public function store($data, $item);
    public function delete($itemOffer);
}
