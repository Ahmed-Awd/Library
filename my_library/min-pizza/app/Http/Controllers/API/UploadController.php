<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(UploadImageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $image = imageStore($validated['image']);
        return response()->json(['image' => $image]);
    }

    public function uploadLogo(UploadImageRequest $request):JsonResponse
    {
        $validated = $request->validated();
        $image = storeLogo($validated['image']);
        return response()->json(['image' => $image]);
    }
}
