<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 11:17 AM
 */

namespace App\Repositories;

use App\Models\DriverTemp;

class DriverTempRepository implements DriverTempRepositoryInterface
{

    private DriverTemp $driver;
    public function __construct(DriverTemp $driver)
    {
        $this->driver = $driver;
    }

    public function get()
    {
        return $this->driver->with('driver')->paginate(15);
    }

    public function store($data)
    {
        return  $this->driver->create($data);
    }

    public function show($driver)
    {
        return $this->driver->where('id', $driver)->with(['files','driver'])->first();
    }

    public function delete($driver)
    {
        $driver->delete();
    }
}
