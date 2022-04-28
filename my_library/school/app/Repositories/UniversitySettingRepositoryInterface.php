<?php

namespace App\Repositories;

interface UniversitySettingRepositoryInterface
{
    public function get($program, $track);

    public function set($program, $track, $data);
}
