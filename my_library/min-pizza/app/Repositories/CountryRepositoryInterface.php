<?php

namespace App\Repositories;

use App\Models\Country;

interface CountryRepositoryInterface
{
    public function getCountries();
    public function cities(Country $country);
}
