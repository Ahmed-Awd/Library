<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 4:45 PM
 */

namespace App\Repositories;

interface PackageRepositoryInterface
{
    public function get($type = "all");
    public function show($package);
    public function store($data);
    public function update($package, $data);
    public function delete($package);
}
