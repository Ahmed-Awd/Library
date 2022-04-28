<?php

namespace App\Repositories;

interface SettingRepositoryInterface
{
    public function get();

    public function show($setting);

    public function update($key, $value);
}
