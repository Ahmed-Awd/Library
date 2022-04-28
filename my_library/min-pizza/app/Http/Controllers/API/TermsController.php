<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAboutUsRequest;
use App\Http\Requests\UpdateTermsRequest;
use App\Repositories\TermsRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class TermsController extends Controller
{
    private TermsRepositoryInterface $termsRepository;

    public function __construct(TermsRepositoryInterface $termsRepository)
    {
        $this->termsRepository = $termsRepository;
    }

    public function getAboutUs(): JsonResponse
    {
        $data = $this->termsRepository->show('about_us');
        return response()->json(['about_us' => $data]);
    }

    public function getTerms(): JsonResponse
    {
        $data = $this->termsRepository->show('terms_and_conditions');
        return response()->json(['terms' => $data]);
    }

    public function updateTerms(UpdateTermsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $info['value'] = json_encode([
            "en" => $data['english_value'],
            "sv" => $data['swedish_value']
        ]);
        $this->termsRepository->update('terms_and_conditions', $info);
        return response()->json(['message' => Lang::get('messages.terms.info_updated')]);
    }

    public function updateAboutUs(UpdateAboutUsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $info['value'] = json_encode([
            "en" => $data['english_value'],
            "sv" => $data['swedish_value']
        ]);
        $this->termsRepository->update('about_us', $info);
        return response()->json(['message' => Lang::get('messages.terms.info_updated')]);
    }
}
