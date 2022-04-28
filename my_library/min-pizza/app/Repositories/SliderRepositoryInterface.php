<?php

namespace App\Repositories;

interface SliderRepositoryInterface
{
    public function get($type = "all");
    public function show($slider);
    public function store($data);
    public function update($slider, $data);
    public function changeStatus($slider);
    public function delete($slider);
}
