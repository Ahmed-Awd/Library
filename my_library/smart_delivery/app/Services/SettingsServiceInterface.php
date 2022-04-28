<?php

namespace App\Services;

interface SettingsServiceInterface
{
    public function get(string $key): ?string;

    public function set(string $key, string $value): void;
}
