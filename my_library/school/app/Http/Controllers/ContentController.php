<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Content;
use App\Repositories\ContentRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    private ContentRepositoryInterface $contentRepository;

    public function __construct(ContentRepositoryInterface $contentRepository)
    {
        $this->authorizeResource(Content::class);
        $this->contentRepository = $contentRepository;
    }

    public function index(): JsonResponse
    {
        $contents = $this->contentRepository->get();
        return response()->json($contents);
    }


    public function store(StoreContentRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->contentRepository->store($validated);
        return response()->json(["message" => "lms content created successfully"]);
    }


    public function show(Content $content): JsonResponse
    {
        $content = $this->contentRepository->show($content);
        return response()->json($content);
    }

    public function update(UpdateContentRequest $request, Content $content): JsonResponse
    {
        $validated = $request->validated();
        $this->contentRepository->update($content,$validated);
        return response()->json(["message" => "lms content updated successfully"]);
    }


    public function destroy(Content $content): JsonResponse
    {
        $this->contentRepository->delete($content);
        return response()->json(["message" => "lms content deleted successfully"]);
    }
}
