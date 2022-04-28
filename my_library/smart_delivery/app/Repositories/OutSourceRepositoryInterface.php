<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 4:45 PM
 */

namespace App\Repositories;

interface OutSourceRepositoryInterface
{
    public function get();
    public function show($user);
    public function store($data);
    public function update($user, $data);
    public function disabled($user);
    public function delete($user);
}
