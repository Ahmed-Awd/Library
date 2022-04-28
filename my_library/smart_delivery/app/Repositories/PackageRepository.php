<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 11:17 AM
 */

namespace App\Repositories;

use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PackageRepository implements PackageRepositoryInterface
{
    private Package $package;
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    public function get($type = "all")
    {
        if ($type == "all") {
            return $this->package->paginate(15);
        }
        return $this->package->where('type', $type)->paginate(15);
    }

    public function store($data)
    {
        $data['value'] = $data['value'] * 100;
        $data['price'] = $data['price'] * 100;
        $this->package->create($data);
    }

    public function update($package, $data)
    {
        $data['value'] = $data['value'] * 100;
        $data['price'] = $data['price'] * 100;
        $package->update($data);
    }

    public function delete($package)
    {
        $package->delete();
    }

    public function show($package)
    {
        return $this->package->where('id', $package)->first();
    }
}
