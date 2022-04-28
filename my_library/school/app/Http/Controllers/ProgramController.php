<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Models\Program;
use App\Repositories\ProgramRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ProgramController extends Controller
{
    private ProgramRepositoryInterface $programRepository;

    public function __construct(ProgramRepositoryInterface $programRepository)
    {
        $this->authorizeResource(Program::class,'program');
        $this->programRepository = $programRepository;
    }

    public function index(): JsonResponse
    {
        $programs = $this->programRepository->get();
        return response()->json($programs);
    }


    public function store(StoreProgramRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->programRepository->store($validated);
        return response()->json(["message" => "program created successfully"]);
    }


    public function show(Program $program): JsonResponse
    {
        $program = $this->programRepository->show($program);
        return response()->json($program);
    }


    public function update(UpdateProgramRequest $request, Program $program): JsonResponse
    {
        $validated = $request->validated();
        $this->programRepository->update($program,$validated);
        return response()->json(["message" => "program updated successfully"]);
    }

    public function destroy(Program $program): JsonResponse
    {
        $this->programRepository->delete($program);
        return response()->json(["message" => "program deleted successfully"]);
    }
}
