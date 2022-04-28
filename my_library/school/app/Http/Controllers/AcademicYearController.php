<?php

namespace App\Http\Controllers;

use App\Models\Academic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreYearRequest;
use App\Repositories\AcademicYearRepositoryInterface;


class AcademicYearController extends Controller
{

    private AcademicYearRepositoryInterface $academicYearRepository;

    public function __construct(AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->authorizeResource(Academic::class);
        $this->academicYearRepository = $academicYearRepository;
    }

    public function index(Request $request): JsonResponse {
        $academicYears = $this->academicYearRepository->get();
        return response()->json($academicYears);
    }

    public function store(StoreYearRequest $request): JsonResponse {
        $validated = $request->validated();
        $this->academicYearRepository->store($validated);
        return response()->json(["message" => "academic year created successfully"]);
    }

    public function show(Academic $academic): JsonResponse {
        $academic = $this->academicYearRepository->show($academic);
        return response()->json($academic);
    }

    public function update(StoreYearRequest $request,Academic $academic): JsonResponse {
        $validated = $request->validated();
        $this->academicYearRepository->update($academic,$validated);
        return response()->json(["message" => "academic year updated successfully"]);
    }

}
