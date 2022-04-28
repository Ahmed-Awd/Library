<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignStaffRequest;
use App\Http\Requests\StoreSubjectConfig;
use App\Http\Requests\StoreSubjectRequest;
use App\Models\AcademicSemester;
use App\Models\ClassRoom;
use App\Repositories\SubjectRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private SubjectRepositoryInterface $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepository)
    {
        $this->authorizeResource(Subject::class);
        $this->subjectRepository = $subjectRepository;
    }

    public function index(): JsonResponse
    {
        $subjects = $this->subjectRepository->get();
        return response()->json($subjects);
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->subjectRepository->store($validated);
        return response()->json(["message" => "subject created successfully"]);
    }


    public function show(Subject $subject): JsonResponse
    {
        return response()->json($subject);
    }

    public function update(StoreSubjectRequest $request, Subject $subject): JsonResponse
    {
        $validated = $request->validated();
        $this->subjectRepository->update($subject->id, $validated);
        return response()->json(["message" => "subject updated successfully"]);
    }


    public function destroy($id): JsonResponse
    {
        $this->subjectRepository->delete($id);
        return response()->json(["message" => "subject deleted successfully"]);
    }

    public function showSubjectConfig($id): JsonResponse
    {
        $subjectConfig = $this->subjectRepository->showSubjectConfig($id);
        return response()->json($subjectConfig);
    }

    public function updateSubjectConfig(StoreSubjectConfig $request, $id): JsonResponse
    {
//        $validated = $request->validate();
        $this->subjectRepository->updateSubjectConfig($request, $id);
        return response()->json(["message" => "subject updated successfully"]);
    }

    public function setStaff(AcademicSemester $semester,AssignStaffRequest $request,ClassRoom $class = null): JsonResponse
    {
        $validated = $request->validated();
        $subjects = $validated['subjects'];
        foreach ($subjects as $key => $value){
           $this->subjectRepository->setStaff($semester->id,$class,$key,$value);
        }
        return response()->json(["message" => "subject assigned to staff successfully"]);
    }

    public function getStaff(AcademicSemester $semester,ClassRoom $class = null): JsonResponse
    {
        $class == null ? $subjects = $this->subjectRepository->getUniversityStaff($semester)
            : $subjects = $this->subjectRepository->getSchoolStaff($semester,$class);

        return response()->json($subjects);
    }

    public function getTopics(Subject $subject,AcademicSemester $semester = null)
    {
        $topics = $this->subjectRepository->getTopics($subject,$semester);
        return response()->json($topics);
    }
}
