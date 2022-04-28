<?php


namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Level;
use App\Models\Program;
use App\Models\Track;
use App\Repositories\TimetableRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    private TimetableRepositoryInterface $timetableRepository;

    public function __construct(TimetableRepositoryInterface $timetableRepository)
    {
        $this->timetableRepository = $timetableRepository;

    }

    public function getUserSchedule($username) :JsonResponse {
        $this->authorize('view timetable');
        $result = $this->timetableRepository->getUserSchedule($username);
        return response()->json($result);
    }
    public function getClassSchedule(ClassRoom $class) :JsonResponse {
        $this->authorize('view timetable');

        $result = $this->timetableRepository->getClassSchedule($class);
        return response()->json($result);
    }
    public function updateSchedule(Request $request) :JsonResponse {
        $this->authorize('update timetable');

        $result = $this->timetableRepository->updateSchedule($request);
        return response()->json(['message'=>'schedule updated succefully']);
    }
    public function isStaffAvailable(Request $request) :JsonResponse {
        $this->authorize('view timetable');

        $result = $this->timetableRepository->isStaffAvailable($request);
        return response()->json($result);
    }

    public function getLevelSchedule(Program $program, Track $track, Level $level) :JsonResponse {
        $this->authorize('view timetable');

        $result = $this->timetableRepository->getLevelSchedule($program, $track, $level);
        return response()->json($result);
    }

    public function StaffReport(Request $request) :JsonResponse {
        $this->authorize('list timetable');

        $result = $this->timetableRepository->StaffReport($request);
        return response()->json($result);
    }

    public function classesReport(Request $request) :JsonResponse {
        $this->authorize('list timetable');

        $result = $this->timetableRepository->classesReport($request);
        return response()->json($result);
    }
}
