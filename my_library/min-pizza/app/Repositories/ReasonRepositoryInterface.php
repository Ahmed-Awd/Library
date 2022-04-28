<?php

namespace App\Repositories;

interface ReasonRepositoryInterface
{
    public function get();
    public function show($refuseReason);
    public function store($data);
    public function update($refuseReason, $data);
    public function delete($refuseReason);
}
