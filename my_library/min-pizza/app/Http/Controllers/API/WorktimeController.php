<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorktimeRequest;
use App\Models\Restaurant;
use App\Repositories\DayRepository;
use App\Repositories\WorktimeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class WorktimeController extends Controller
{
    //
    private DayRepository $dayRepository;
    private WorktimeRepositoryInterface $worktimeRepository;
    public function __construct(
        DayRepository $dayRepository,
        WorktimeRepositoryInterface $worktimeRepository
    ) {
        $this->dayRepository = $dayRepository;
        $this->worktimeRepository = $worktimeRepository;
    }

    public function days(): JsonResponse
    {
        $days = $this->dayRepository->get();
        return response()->json(['days' => $days], 200);
    }
    public function store(StoreWorktimeRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $this->worktimeRepository->delete($restaurant->id);
        if (isset($validated['Worktimes'])) {
            foreach ($validated['Worktimes'] as $worktime) {
                $this->worktimeRepository->store($restaurant->id, $worktime);
            }
        }
        return response()->json(["message" => Lang::get('messages.worktime.create')], 200);
    }

    public function show(Restaurant $restaurant): JsonResponse
    {
        $worktimes = $this->worktimeRepository->get($restaurant->id);
        return response()->json(['worktimes' => $worktimes], 200);
    }
}
