<?php

namespace App\Repositories;

interface FeedBackRepositoryInterface
{
    public function get($search, $order);

    public function store($data);

    public function show($feedback);

    public function delete($feedback);

    public function replay($feedback, $data);
}
