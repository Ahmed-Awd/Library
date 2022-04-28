<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOptionValueRequest;
use App\Http\Requests\UpdateOptionValueRequest;
use App\Models\OptionCategory;
use App\Models\OptionValue;
use App\Repositories\OptionValueRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class OptionValueController extends Controller
{

    private OptionValueRepositoryInterface $valueRepository;


    public function __construct(OptionValueRepositoryInterface $valueRepository)
    {
        $this->valueRepository = $valueRepository;
        $this->authorizeResource(OptionValue::class);
    }

    public function index(OptionCategory $optionCategory): JsonResponse
    {
        $data = $this->valueRepository->get($optionCategory);
        return response()->json(['values' => $data , 'category' => $optionCategory]);
    }


    public function store(StoreOptionValueRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->valueRepository->store($data);
        return response()->json(["message" => Lang::get('messages.option_value.create')]);
    }


    public function show(OptionValue $optionValue): JsonResponse
    {
        $value = $this->valueRepository->show($optionValue);
        return response()->json(['value' => $value]);
    }


    public function update(UpdateOptionValueRequest $request, OptionValue $optionValue): JsonResponse
    {
        $data = $request->validated();
        $this->valueRepository->update($optionValue, $data);
        return response()->json(["message" => Lang::get('messages.option_value.update')]);
    }


    public function destroy(OptionValue $optionValue): JsonResponse
    {
        $this->valueRepository->delete($optionValue);
        return response()->json(["message" => Lang::get('messages.option_value.delete')]);
    }
}
