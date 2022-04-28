<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimingSetRequest;
use App\Http\Requests\UpdateTimingSetRequest;
use App\Models\TimingSet;
use App\Repositories\TimingSetRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimingSetController extends Controller
{

    private TimingSetRepository $timingSetRepository;

    public function __construct(TimingSetRepository $timingSetRepository)
    {
        $this->authorizeResource(TimingSet::class);
        $this->timingSetRepository = $timingSetRepository;
    }

    public function index(): JsonResponse
    {
        $sets = $this->timingSetRepository->get();
        return response()->json($sets);
    }


    public function store(StoreTimingSetRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->timingSetRepository->store($validated);
        return response()->json(["message" => "time set created successfully"]);
    }


    public function show(TimingSet $timingSet): JsonResponse
    {
        $set = $this->timingSetRepository->show($timingSet);
        return response()->json($set);
    }


    public function update(UpdateTimingSetRequest $request, TimingSet $timingSet): JsonResponse
    {
        $validated = $request->validated();
        $this->timingSetRepository->update($timingSet,$validated);
        return response()->json(["message" => "time set updated successfully"]);
    }


    public function destroy(TimingSet $timingSet): JsonResponse
    {
        $this->timingSetRepository->delete($timingSet);
        return response()->json(["message" => "time set deleted successfully"]);
    }
}
