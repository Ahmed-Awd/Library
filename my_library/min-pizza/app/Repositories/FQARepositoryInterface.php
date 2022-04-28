<?php

namespace App\Repositories;

interface FQARepositoryInterface
{
    public function get($search, $order);

    public function show($FQA);

    public function update($FQA, $data);

    public function store($data);

    public function destroy($FQA);

    public function all();
}
