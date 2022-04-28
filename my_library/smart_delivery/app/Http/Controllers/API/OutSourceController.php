<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\OutSourceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OutSourceController extends Controller
{
    private OutSourceRepositoryInterface $outSourceRepository;

    public function __construct(OutSourceRepositoryInterface $outSourceRepository)
    {
        $this->outSourceRepository = $outSourceRepository;
    }

    public function data(): JsonResponse
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
        ]);
    }
}
