<?php

namespace App\Repositories;

use App\Models\User;

interface ContactRepositoryInterface
{
    public function get();
    public function show($contact);
    public function create($data);
    public function delete($contact);
}
