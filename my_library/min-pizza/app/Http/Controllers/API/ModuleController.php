<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateModulesRequest;
use App\Models\Module;
use App\Repositories\ModuleRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class ModuleController extends Controller
{
    private ModuleRepositoryInterface $moduleRepository;

    public function __construct(ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->moduleRepository->get();
        return response()->json(['modules' => $data]);
    }


    public function getByKey($key): JsonResponse
    {
        $module = Module::getModuleByKey($key);
        return response()->json(['module' => $module]);
    }

    public function update(UpdateModulesRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->moduleRepository->update($validated['key'], $validated['value']);
        return response()->json(['message' => Lang::get('messages.update')]);
    }
}
