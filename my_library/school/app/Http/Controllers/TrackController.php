<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignSubjectRequest;
use App\Http\Requests\StoreTrackRequest;
use App\Http\Requests\UpdateTrackRequest;
use App\Models\AcademicSemester;
use App\Models\Level;
use App\Models\Program;
use App\Models\Track;
use App\Repositories\TrackRepositoryInterface;
use http\Env\Request;
use Illuminate\Http\JsonResponse;

class TrackController extends Controller
{

    private TrackRepositoryInterface $trackRepository;

    public function __construct(TrackRepositoryInterface $trackRepository)
    {
        $this->authorizeResource(Track::class,'track');
        $this->trackRepository = $trackRepository;
    }

    public function index(): JsonResponse
    {
        $tracks = $this->trackRepository->get();
        return response()->json($tracks);
    }


    public function store(StoreTrackRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->trackRepository->store($validated);
        return response()->json(["message" => "track created successfully"]);
    }


    public function show(Track $track): JsonResponse
    {
        $track = $this->trackRepository->show($track);
        return response()->json($track);
    }


    public function update(UpdateTrackRequest $request, Track $track): JsonResponse
    {
        $validated = $request->validated();
        $this->trackRepository->update($track,$validated);
        return response()->json(["message" => "track updated successfully"]);
    }


    public function destroy(Track $track): JsonResponse
    {
        $this->trackRepository->delete($track);
        return response()->json(["message" => "track deleted successfully"]);
    }

    public function AssignSubjects(Track $track,Program $program,AssignSubjectRequest $request,Level $level = null): JsonResponse
    {
        $data = $request->validated();
        $level == null ? $this->trackRepository->setOptionalSubjects($track,$program,$data['semester'],$data['subjects'],$data['is_mandatory'])
            :$this->trackRepository->setMandatorySubjects($track,$program,$level,$data['semester'],$data['subjects'],$data['is_mandatory']);

        return response()->json(["message" => "track subjects assigned successfully"]);
    }

    public function AssignedSubjects(Track $track,Program $program,AcademicSemester $semester,Level $level = null): JsonResponse
    {
        $level == null ? $subjects = $this->trackRepository->getOptionalSubjects($track,$program,$semester)
            : $subjects = $this->trackRepository->getMandatorySubjects($track,$program,$level,$semester);

        return response()->json($subjects);
    }


}
