<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use App\Repositories\AttachmentRepositoryInterface;
use Illuminate\Http\Request;

class UploadController extends Controller
{

    private AttachmentRepositoryInterface $attachmentRepository;

    public function __construct(AttachmentRepositoryInterface $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    public function upload(UploadImageRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $record = $this->attachmentRepository->store($data['image']);
        return response()->json(['image' => $record]);
    }

    public function uploadOnlyFile(UploadImageRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $record = saveImage($data['image']);
        return response()->json(['image' => $record]);
    }
}
