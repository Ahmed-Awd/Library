<?php

namespace App\Repositories;

use App\Models\User;

interface AddressRepositoryInterface
{
    public function get();
    public function show($address);
    public function create($data);
    public function update($data, $address);
    public function delete($address);
    public function userAddresses(User $user);
    public function setDefault($address, $user);
}
