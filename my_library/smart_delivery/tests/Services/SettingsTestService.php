<?php

namespace Tests\Services;

use App\Services\SettingsServiceInterface;

class SettingsTestService implements SettingsServiceInterface
{
    public static $keys = [];

    public function get(string $key): ?string
    {
        return self::$keys[$key];
    }

    public function set(string $key, string $value): void
    {
        self::$keys[$key] = $value;
    }

    public function setAll(array $keys)
    {
        self::$keys = $keys;
    }
}
