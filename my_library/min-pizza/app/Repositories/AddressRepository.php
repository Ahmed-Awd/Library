<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AddressRepository implements AddressRepositoryInterface
{

    private Address $address;


    public function __construct(Address $address)
    {
        $this->address = $address;
    }


    public function get()
    {
        return Auth::user()->addresses;
    }

    public function show($address)
    {
        return  $address;
    }

    public function create($data)
    {
        $this->address->create($data);
    }


    public function update($data, $address)
    {
        $address->update($data);
    }

    public function delete($address)
    {
        $address->delete();
    }

    public function userAddresses(User $user)
    {
        return $user->addresses;
    }

    public function setDefault($address, $user)
    {
        $user->addresses()->update(['is_default' => 0]);
        $address->update(['is_default' => 1]);
    }
}
