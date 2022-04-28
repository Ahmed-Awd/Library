<?php

namespace App\Repositories;

use App\Models\Module;

class ModuleRepository implements ModuleRepositoryInterface
{
    private Module $modules;

    public function __construct(Module $module)
    {
        $this->modules = $module;
    }

    public function get()
    {
        return $this->modules->all();
    }

    public function update($key, $value)
    {
        $current = $this->modules->where('key', $key)->first();
        if ($current) {
            $current->value = $value;
            $current->save();
        }
    }
}
