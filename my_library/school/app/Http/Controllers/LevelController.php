<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignLevelRequest;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Models\Level;
use App\Models\Program;
use App\Models\Track;
use App\Repositories\LevelRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelController extends Controller
{

    private LevelRepositoryInterface $levelRepository;

    public function __construct(LevelRepositoryInterface $levelRepository)
    {
        $this->authorizeResource(Level::class,'level');
        $this->levelRepository = $levelRepository;
    }

    public function index(): JsonResponse
    {
        $levels = $this->levelRepository->get();
        return response()->json($levels);
    }


    public function store(StoreLevelRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->levelRepository->store($validated);
        return response()->json(["message" => "level created successfully"]);
    }


    public function show(Level $level): JsonResponse
    {
        $level = $this->levelRepository->show($level);
        return response()->json($level);
    }


    public function update(UpdateLevelRequest $request, Level $level): JsonResponse
    {
        $validated = $request->validated();
        $this->levelRepository->update($level,$validated);
        return response()->json(["message" => "level updated successfully"]);
    }

    public function destroy(Level $level): JsonResponse
    {
        $this->levelRepository->delete($level);
        return response()->json(["message" => "level deleted successfully"]);
    }

    public function getAssigned(Program $program,Track $track): JsonResponse
    {
        $levels =  $this->levelRepository->fetchLevels($program,$track);
        return response()->json($levels);
    }

    public function setAssigned(Program $program,Track $track,AssignLevelRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->levelRepository->setLevels($program,$track,$validated['levels']);
        return response()->json(["message" => "levels Assigned successfully"]);
    }


}
