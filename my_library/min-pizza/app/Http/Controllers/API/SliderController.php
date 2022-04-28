<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Slider;
use App\Repositories\SliderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SliderController extends Controller
{


    private SliderRepositoryInterface $sliderRepository;


    public function __construct(SliderRepositoryInterface $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }

    public function all(): JsonResponse
    {
        $sliders = $this->sliderRepository->get('active-only');
        return response()->json(['sliders' => $sliders]);
    }

    public function get(): JsonResponse
    {
        $sliders = $this->sliderRepository->get();
        return response()->json(['sliders' => $sliders]);
    }


    public function store(StoreSliderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['title'] = translate($validated['title']);
        $validated['content'] = translate($validated['content']);
        $this->sliderRepository->store($validated);
        return response()->json(["message" => Lang::get('messages.create')]);
    }


    public function show(Slider $slider): JsonResponse
    {
        $slider = $this->sliderRepository->show($slider);
        return response()->json(['slider' => $slider]);
    }


    public function update(UpdateSliderRequest $request, Slider $slider): JsonResponse
    {
        $validated = $request->validated();
        if (isset($validated['title'])) {
            $validated['title'] = translate($validated['title']);
        }
        if (isset($validated['content'])) {
            $validated['content'] = translate($validated['content']);
        }
        $this->sliderRepository->update($slider, $validated);
        return response()->json(["message" => Lang::get('messages.update')]);
    }


    public function destroy(Slider $slider): JsonResponse
    {
        $this->sliderRepository->delete($slider);
        return response()->json(["message" => Lang::get('messages.delete')]);
    }

    public function switchStatus(Slider $slider): JsonResponse
    {
        $this->sliderRepository->changeStatus($slider);
        return response()->json(["message" => Lang::get('messages.switch')]);
    }
}
