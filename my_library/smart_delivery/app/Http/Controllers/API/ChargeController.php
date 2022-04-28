<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChargeRequest;
use App\Repositories\PackageCodeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChargeController extends Controller
{
    private PackageCodeRepositoryInterface $packageCodeRepository;

    public function __construct(PackageCodeRepositoryInterface $packageCodeRepository)
    {
        $this->packageCodeRepository = $packageCodeRepository;
    }

    public function charge(ChargeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->packageCodeRepository->charge($data['code']);
        return response()->json(['message' => $response['message']], $response['code']);
    }
}
