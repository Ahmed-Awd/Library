<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 4:45 PM
 */

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function get($role);

    public function show($user);

    public function delete($user);

    public function store($data);

    public function update($user, $data);

    public function imageStore($request);

    public function suspend($user);

    public function unsuspend($user);

}
