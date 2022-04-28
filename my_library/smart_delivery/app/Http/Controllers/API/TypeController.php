<?php

namespace App\Http\Controllers\API;

use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TypeController extends Controller
{
    private TypeRepositoryInterface $typeRepository;

    public function __construct(TypeRepositoryInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }
    public function index(): JsonResponse
    {
        $types = $this->typeRepository->get();
        return response()->json([
            'types' => $types,
        ], 200);
    }
}
