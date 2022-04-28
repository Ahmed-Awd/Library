<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplayFeedBackRequest;
use App\Http\Requests\SendFeedBackRequest;
use App\Models\FeedBack;
use App\Repositories\FeedBackRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class FeedBackController extends Controller
{
    private FeedBackRepositoryInterface $feedBackRepository;

    public function __construct(FeedBackRepositoryInterface $feedBackRepository)
    {
        $this->feedBackRepository = $feedBackRepository;
    }


    public function index(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $feedbacks = $this->feedBackRepository->get($search, $order);
        return response()->json(['feedbacks' => $feedbacks]);
    }

    public function send(SendFeedBackRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->feedBackRepository->store($data);
        return response()->json(['message' => Lang::get('messages.feedback.send')]);
    }

    public function show($feedBack): JsonResponse
    {
        $feedBack = $this->feedBackRepository->show($feedBack);
        return response()->json(['record' => $feedBack]);
    }

    public function delete(FeedBack $feedback): JsonResponse
    {
        $this->feedBackRepository->delete($feedback);
        return response()->json(["message" => Lang::get('messages.feedback.delete')]);
    }

    public function replay(ReplayFeedBackRequest $request, FeedBack $feedback): JsonResponse
    {
        $data = $request->validated();
        $message = $this->feedBackRepository->replay($feedback, $data);
        return response()->json(["message" => $message]);
    }
}
