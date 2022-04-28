<?php

namespace App\Repositories;

interface StoreOutSourceRepositoryInterface
{
    public function get($filter);

    public function show($store);

    public function store($data);

    public function update($store, $data);

    public function delete($store);
}
