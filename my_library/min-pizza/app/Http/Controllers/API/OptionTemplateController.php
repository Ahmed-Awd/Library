<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOptionTemplateRequest;
use App\Models\OptionCategory;
use App\Models\OptionSecondary;
use App\Models\OptionTemplate;
use App\Repositories\OptionSecondaryRepositoryInterface;
use App\Repositories\OptionTemplateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class OptionTemplateController extends Controller
{
    private OptionTemplateRepositoryInterface $optionTemplateRepository;
    private OptionSecondaryRepositoryInterface $optionSecondaryRepository;


    public function __construct(
        OptionTemplateRepositoryInterface $optionTemplateRepository,
        OptionSecondaryRepositoryInterface $optionSecondaryRepository
    ) {
        $this->optionTemplateRepository = $optionTemplateRepository;
        $this->optionSecondaryRepository = $optionSecondaryRepository;
    }
    public function index(): JsonResponse
    {
        $data = $this->optionTemplateRepository->get();
        return response()->json(['templates' => $data]);
    }
    public function store(StoreOptionTemplateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['name'] = translate($data['name']);
        $restaurant = auth()->user()->restaurant ?? null;
        if ($restaurant != null) {
            $data['restaurant_id'] = $restaurant->id;
            $option_secondaries = isset($data['option_secondaries']) ? $data['option_secondaries'] : null;
            if (isset($data['option_secondaries'])) {
                unset($data['option_secondaries']);
            }
            $option_template = $this->optionTemplateRepository->store($data);
            if (!is_null($option_secondaries)) {
                foreach ($option_secondaries as $option_secondary) {
                    $option_secondary['option_template_id'] = $option_template->id;
                    $this->optionSecondaryRepository->store($option_secondary);
                }
            }
        }
        return response()->json(["message" => Lang::get('messages.option_template.create')]);
    }
    public function update(StoreOptionTemplateRequest $request, OptionTemplate $option_template): JsonResponse
    {
        $data = $request->validated();
        $data['name'] = translate($data['name']);
        $restaurant = auth()->user()->restaurant ?? null;
        if ($restaurant != null) {
            $option_secondaries = isset($data['option_secondaries']) ? $data['option_secondaries'] : null;
            if (isset($data['option_secondaries'])) {
                unset($data['option_secondaries']);
            }
            $this->optionTemplateRepository->update($option_template, $data);
            if (!is_null($option_secondaries)) {
                $this->optionSecondaryRepository->delete($option_template);
                foreach ($option_secondaries as $option_secondary) {
                    $option_secondary['option_template_id'] = $option_template->id;
                    $this->optionSecondaryRepository->store($option_secondary);
                }
            }
        }
        return response()->json(["message" => Lang::get('messages.option_template.update')]);
    }
    public function show(OptionTemplate $option_template): JsonResponse
    {
        $option_templates = $this->optionTemplateRepository->show($option_template);
        $option_templates->optionSecondaries = $this->optionSecondaryRepository->get($option_template);
        $secondary_options_selected = OptionSecondary::where('option_template_id', $option_template->id)
        ->groupBy('secondary_option_id')
        ->pluck('secondary_option_id')->toArray();
        return response()->json(['option_template' => $option_templates,
        'secondary_options_selected' => $secondary_options_selected]);
    }
    public function destroy(OptionTemplate $option_template): JsonResponse
    {
        $this->optionTemplateRepository->delete($option_template);
        return response()->json(["message" => Lang::get('messages.option_template.delete')]);
    }
}
