<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignSettingRequest;
use App\Models\Program;
use App\Models\Track;
use App\Repositories\UniversitySettingRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UniversitySettingController extends Controller
{

    private UniversitySettingRepositoryInterface $universitySettingRepository;

    public function __construct(UniversitySettingRepositoryInterface $universitySettingRepository)
    {
        $this->universitySettingRepository = $universitySettingRepository;
    }

    public function getSettings(Program $program,Track $track): JsonResponse
    {
        $settings = $this->universitySettingRepository->get($program,$track);
        return response()->json($settings);
    }

    public function setSettings(Program $program,Track $track,AssignSettingRequest $request):JsonResponse
    {
        $validated = $request->validated();
        $this->universitySettingRepository->set($program,$track,$validated);
        return response()->json(["message" => "settings set successfully"]);
    }
}
