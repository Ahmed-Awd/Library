<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\TownRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TownController extends Controller
{

    private TownRepositoryInterface $townRepository;

    public function __construct(TownRepositoryInterface $townRepository)
    {
        $this->townRepository = $townRepository;
    }

    public function index(): JsonResponse
    {
        $towns = $this->townRepository->get();
        return response()->json(['towns' => $towns]);
    }
}
