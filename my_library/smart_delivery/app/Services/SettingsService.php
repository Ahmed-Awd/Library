<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public function get(string $key): ?string
    {
        return Setting::firstWhere('key', $key)->value;
    }

    public function set(string $key, string $value): void
    {
        Setting::where('key', $key)->update(compact('value'));
        Cache::forget('settings.' . $key);
    }
}
