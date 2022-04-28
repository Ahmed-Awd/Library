<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteFileRequest;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Models\Feedback;
use App\Repositories\FeedbackRepositoryInterface;
use Illuminate\Http\JsonResponse;

class FeedbackController extends Controller
{

    private FeedbackRepositoryInterface $feedbackRepository;

    public function __construct(FeedbackRepositoryInterface $feedbackRepository)
    {
        $this->authorizeResource(Feedback::class);
        $this->feedbackRepository = $feedbackRepository;
    }

    public function index(): JsonResponse
    {
        $feedbacks = $this->feedbackRepository->get();
        return response()->json($feedbacks);
    }


    public function store(StoreFeedbackRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->feedbackRepository->store($validated);
        return response()->json(["message" => "feedback created successfully"]);
    }


    public function show(Feedback $feedback): JsonResponse
    {
        $feedback = $this->feedbackRepository->show($feedback);
        return response()->json($feedback);
    }


    public function update(UpdateFeedbackRequest $request, Feedback $feedback): JsonResponse
    {
        $validated = $request->validated();
        $this->feedbackRepository->update($feedback,$validated);
        return response()->json(["message" => "feedback updated successfully"]);
    }


    public function destroy(Feedback $feedback): JsonResponse
    {
        $this->feedbackRepository->delete($feedback);
        return response()->json(["message" => "feedback deleted successfully"]);
    }

    public function removeFile(Feedback $feedback,DeleteFileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->feedbackRepository->removeFile($feedback,$validated['file']);
        return response()->json(["message" => "file deleted successfully"]);
    }
}
