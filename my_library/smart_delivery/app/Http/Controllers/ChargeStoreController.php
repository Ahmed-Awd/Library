<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargeRequest;
use App\Repositories\PackageCodeRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ChargeStoreController extends Controller
{
    private PackageCodeRepositoryInterface $packageCodeRepository;

    public function __construct(PackageCodeRepositoryInterface $packageCodeRepository)
    {
        $this->packageCodeRepository = $packageCodeRepository;
    }

    public function charge(ChargeRequest $request)
    {
        $data = $request->validated();
        $response = $this->packageCodeRepository->charge($data['code']);
        session()->flash('flash.banner', $response['message']);
        if ($response['code'] == 200) {
            session()->flash('flash.bannerStyle', 'success');
        } else {
            session()->flash('flash.bannerStyle', 'danger');
        }
        return back();
    }
}
