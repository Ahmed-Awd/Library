<?php

namespace App\Repositories;

interface ModuleRepositoryInterface
{
    public function get();
    public function update($key, $value);
}
