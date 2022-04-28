<?php

namespace App\Repositories;

interface StoreRepositoryInterface
{
    public function get($filter = false);

    public function show($store);

    public function store($data);

    public function update($store, $data);

    public function delete($store);

    public function saveSettings($store, $data);

    public function updateMyStore($data);

    public function all();
}
