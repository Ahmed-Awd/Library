<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstructionRequest;
use App\Http\Requests\UpdateInstructionRequest;
use App\Models\Instruction;
use App\Repositories\InstructionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class InstructionController extends Controller
{

    private InstructionRepositoryInterface $instructionRepository;

    public function __construct(InstructionRepositoryInterface $instructionRepository)
    {
        $this->authorizeResource(Instruction::class);
        $this->instructionRepository = $instructionRepository;
    }

    public function index(): JsonResponse
    {
        $instructions = $this->instructionRepository->get();
        return response()->json($instructions);
    }


    public function store(StoreInstructionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->instructionRepository->store($validated);
        return response()->json(["message" => "instruction created successfully"]);
    }


    public function show(Instruction $instruction): JsonResponse
    {
        $instruction = $this->instructionRepository->show($instruction);
        return response()->json($instruction);
    }


    public function update(UpdateInstructionRequest $request, Instruction $instruction): JsonResponse
    {
        $validated = $request->validated();
        $this->instructionRepository->update($instruction,$validated);
        return response()->json(["message" => "instruction updated successfully"]);
    }


    public function destroy(Instruction $instruction): JsonResponse
    {
        $this->instructionRepository->delete($instruction);
        return response()->json(["message" => "instruction deleted successfully"]);
    }
}
