<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Repositories\CountryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CountryController extends Controller
{
    private CountryRepositoryInterface $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function countries(): JsonResponse
    {
        $countries = $this->countryRepository->getCountries();
        return response()->json(['countries' => $countries]);
    }

    public function cities(Country $country): JsonResponse
    {
        $cities = $this->countryRepository->cities($country);
        return response()->json(['cities' => $cities]);
    }
    public function seed(Country $country): JsonResponse
    {
        Artisan::call('db:seed --class=GeneralSettingsSeeder');
        return response()->json(['gmaps' => 'done']);
    }
}
