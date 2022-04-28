<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Models\FQA;
use App\Repositories\FQARepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FQAController extends Controller
{

    private FQARepositoryInterface $FQARepository;

    public function __construct(FQARepositoryInterface $FQARepository)
    {
        $this->FQARepository = $FQARepository;
    }


    public function index(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $questions = $this->FQARepository->get($search, $order);
        return response()->json(['questions' => $questions]);
    }

    public function all(): JsonResponse
    {
        $questions = $this->FQARepository->all();
        return response()->json(['questions' => $questions]);
    }


    public function store(StoreQuestionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data = $this->translate($data);
        $this->FQARepository->store($data);
        return response()->json(["message" => Lang::get('messages.question.create')]);
    }


    public function show(FQA $FQA): JsonResponse
    {
        $question = $this->FQARepository->show($FQA);
        return response()->json(['question' => $question]);
    }


    public function update(StoreQuestionRequest $request, FQA $FQA): JsonResponse
    {
        $data = $request->validated();
        $data = $this->translate($data);
        $this->FQARepository->update($FQA, $data);
        return response()->json(["message" => Lang::get('messages.question.update')]);
    }


    public function destroy(FQA $FQA): JsonResponse
    {
        $this->FQARepository->destroy($FQA);
        return response()->json(["message" => Lang::get('messages.question.delete')]);
    }

    public function translate($data)
    {
        $data['question'] = translate($data['question']);
        $data['answer'] = translate($data['answer']);
        return $data;
    }
}
