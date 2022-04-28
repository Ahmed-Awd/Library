<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\ClassRoom;
use App\Repositories\ClassRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    private ClassRepositoryInterface $classRepository;

    public function __construct(ClassRepositoryInterface $classRepository)
    {
        $this->authorizeResource(ClassRoom::class,'class');
        $this->classRepository = $classRepository;
    }

    public function index(): JsonResponse
    {
        $classes = $this->classRepository->get();
        return response()->json($classes);
    }


    public function store(StoreClassRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->classRepository->store($validated);
        return response()->json(["message" => "class created successfully"]);
    }

    public function show(ClassRoom $class,Request $request): JsonResponse
    {
        $withCourse = $request->get('withCourse',false);
        $class = $this->classRepository->show($class,$withCourse);
        return response()->json($class);
    }

    public function update(UpdateClassRequest $request, ClassRoom $class): JsonResponse
    {
        $validated = $request->validated();
        $this->classRepository->update($class,$validated);
        return response()->json(["message" => "class updated successfully"]);
    }


    public function destroy(ClassRoom $class): JsonResponse
    {
        $this->classRepository->delete($class);
        return response()->json(["message" => "class deleted successfully"]);
    }
}
