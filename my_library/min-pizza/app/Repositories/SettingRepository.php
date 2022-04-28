<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository implements SettingRepositoryInterface
{
    private Setting $settings;

    public function __construct(Setting $setting)
    {
        $this->settings = $setting;
    }

    public function get()
    {
        return $this->settings->all();
    }

    public function show($setting)
    {
        return $setting;
    }

    public function update($key, $value)
    {
        $current = $this->settings->where('key', $key)->first();
        if ($current) {
            if ($this->isValid($current, $value)) {
                $current->value = $value;
                $current->save();
            }
        }
    }

    public function isValid($record, $value): bool
    {
        if ($record->type == "int" && is_numeric($value)) {
            return true;
        }
        if ($record->type == "string" && is_string($value)) {
            return true;
        }
        if ($record->type == "bool") {
            return true;
        }
        return false;
    }

    public function getContactInfo()
    {
        return $this->settings->getContacts();
    }

    public function getMobileInfo()
    {
        return $this->settings->getMobileVersions();
    }
}
