<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository implements SettingRepositoryInterface
{
    private Setting $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }


    public function get()
    {
        return $this->setting->all();
    }

    public function show($setting)
    {
        return $setting;
    }

    public function update($key, $value)
    {
        $this->setting->where('key', $key)->update(['value' => $value]);
    }
}
