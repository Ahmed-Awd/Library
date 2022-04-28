<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReasonRequest;
use App\Http\Requests\UpdateReasonRequest;
use App\Models\RefuseReason;
use App\Repositories\ReasonRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class ReasonsController extends Controller
{

    private ReasonRepositoryInterface $reasonRepository;

    public function __construct(ReasonRepositoryInterface $reasonRepository)
    {
        $this->reasonRepository = $reasonRepository;
    }

    public function all(): JsonResponse
    {
        $reasons = $this->reasonRepository->get();
        return response()->json(['reasons' => $reasons]);
    }


    public function store(StoreReasonRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['reason'] = translate($validated['reason']);
        $this->reasonRepository->store($validated);
        return response()->json(["message" => Lang::get('messages.create')]);
    }


    public function show(RefuseReason $refuseReason): JsonResponse
    {
        $reason = $this->reasonRepository->show($refuseReason);
        return response()->json(['reason' => $reason]);
    }


    public function update(UpdateReasonRequest $request, RefuseReason $refuseReason): JsonResponse
    {
        $validated = $request->validated();
        $validated['reason'] = translate($validated['reason']);
        $this->reasonRepository->update($refuseReason, $validated);
        return response()->json(["message" => Lang::get('messages.update')]);
    }


    public function destroy(RefuseReason $refuseReason): JsonResponse
    {
        $this->reasonRepository->delete($refuseReason);
        return response()->json(["message" => Lang::get('messages.delete')]);
    }
}
