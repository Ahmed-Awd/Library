<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
    private $country;
    private $city;

    public function __construct(Country $country, City $city)
    {
        $this->country = $country;
        $this->city = $city;
    }

    public function getCountries()
    {
        return $this->country->all();
    }

    public function cities(Country $country)
    {
        return $country->cities;
    }
}
