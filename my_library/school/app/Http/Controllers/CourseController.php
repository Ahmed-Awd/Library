<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AllocateCourseSubjectRequest;
use Psy\Util\Json;

class CourseController extends Controller
{

    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->authorizeResource(Course::class, 'course');
        $this->courseRepository = $courseRepository;
    }

    public function index(): JsonResponse
    {
        $courses = $this->courseRepository->get();
        return response()->json($courses);
    }


    public function store(StoreCourseRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->courseRepository->store($validated);
        return response()->json(["message" => "course created successfully"]);
    }

    public function show(Course $course,Request $request): JsonResponse
    {
        $withClasses = $request->get('withClasses',false);
        $course = $this->courseRepository->show($course,$withClasses);
        return response()->json($course);
    }

    public function getClasses(Course $course): JsonResponse
    {
        $classes = $this->courseRepository->getClasses($course);
        return response()->json($classes);
    }


    public function update(UpdateCourseRequest $request, Course $course): JsonResponse
    {
        $validated = $request->validated();
        $this->courseRepository->update($course,$validated);
        return response()->json(["message" => "course updated successfully"]);
    }


    public function destroy(Course $course): JsonResponse
    {
        $this->courseRepository->delete($course);
        return response()->json(["message" => "course deleted successfully"]);
    }

    public function getSubjects(Course $course) : JsonResponse {
        $subjects = $this->courseRepository->getSubjects($course);
        return response()->json($subjects);
    }

    public function setSubjects(AllocateCourseSubjectRequest $request,Course $course) : JsonResponse {
        $this->courseRepository->setSubjects($request, $course);
        return response()->json(['message'=>'subjects added successfully']);
    }
    public function getClassSubjects(ClassRoom $class) {
        $subjects = $this->courseRepository->getClassSubjects($class);
        return response()->json($subjects);
    }

    public function deleteClassSubject(ClassRoom $class, $subject) {
        $subjects = $this->courseRepository->deleteClassSubject($class, $subject);
        return response()->json(["message" => "subject deleted successfully"]);
    }
}
