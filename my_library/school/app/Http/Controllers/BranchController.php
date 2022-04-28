<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Repositories\BranchRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{


    private BranchRepositoryInterface $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository)
    {
        $this->authorizeResource(Branch::class,'branch');
        $this->branchRepository = $branchRepository;
    }

    public function index(): JsonResponse
    {
        $branches = $this->branchRepository->get();
        return response()->json($branches);
    }


    public function store(StoreBranchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->branchRepository->store($validated);
        return response()->json(["message" => "branch created successfully"]);
    }


    public function show(Branch $branch): JsonResponse
    {
        $branch = $this->branchRepository->show($branch);
        return response()->json($branch);
    }


    public function update(UpdateBranchRequest $request, Branch $branch): JsonResponse
    {
        $validated = $request->validated();
        $this->branchRepository->update($branch,$validated);
        return response()->json(["message" => "branch updated successfully"]);
    }

    public function destroy(Branch $branch): JsonResponse
    {
        $this->branchRepository->delete($branch);
        return response()->json(["message" => "branch deleted successfully"]);
    }
}
