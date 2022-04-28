<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaxRequest;
use App\Http\Requests\UpdateTaxRequest;
use App\Models\Tax;
use App\Repositories\TaxRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class TaxesController extends Controller
{


    private TaxRepositoryInterface $taxRepository;

    public function __construct(TaxRepositoryInterface $taxRepository)
    {
        $this->authorizeResource(Tax::class);
        $this->taxRepository = $taxRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->taxRepository->get();
        return response()->json(['taxes' => $data]);
    }

    public function all(): JsonResponse
    {
        $data = $this->taxRepository->get();
        return response()->json(['taxes' => $data]);
    }

    public function store(StoreTaxRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->taxRepository->store($data);
        return response()->json(['message' => Lang::get('messages.Tax.create')]);
    }


    public function show(Tax $tax): JsonResponse
    {
        $tax = $this->taxRepository->show($tax);
        return response()->json(['data' => $tax]);
    }


    public function update(UpdateTaxRequest $request, Tax $tax): JsonResponse
    {
        $data = $request->validated();
        $this->taxRepository->update($tax, $data);
        return response()->json(['message' => Lang::get('messages.Tax.update')]);
    }


    public function destroy(Tax $tax): JsonResponse
    {
        $this->taxRepository->delete($tax);
        return response()->json(["message" => Lang::get('messages.Tax.delete')]);
    }
}
