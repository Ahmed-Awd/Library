<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\DriverTemp;
use App\Repositories\DriverTempRepositoryInterface;
use App\Repositories\AttachmentRepositoryInterface;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreDriverTempRequest;
use Illuminate\Support\Facades\Lang;

class DriverTempController extends Controller
{
    private DriverTempRepositoryInterface $driverTempRepository;
    private AttachmentRepositoryInterface $attachmentRepository;

    public function __construct(
        DriverTempRepositoryInterface $driverTempRepository,
        AttachmentRepositoryInterface $attachmentRepository
    ) {
        $this->driverTempRepository = $driverTempRepository;
        $this->attachmentRepository = $attachmentRepository;
    }

    public function store(StoreDriverTempRequest $request): JsonResponse
    {
          $validated = $request->validated();
            $validated['driver_id'] = auth()->user()->id;
           $driver = $this->driverTempRepository->store($validated);
            $this->uploadFiles($validated, $driver);
            return response()->json(['message' => Lang::get('messages.record.create').','.Lang::get('messages.wait_for_accepted')], 200);
    }

    public function uploadFiles($data, $user)
    {
        $this->attachmentRepository->linkAttachment($user, $data['personal_photo'], "personal_photo");
        $this->attachmentRepository->linkAttachment($user, $data['license_photo'], "license_photo");
        $this->attachmentRepository->linkAttachment($user, $data['vehicle_photo'], "vehicle_photo");
        $this->attachmentRepository->linkAttachment($user, $data['vehicle_license'], "vehicle_license");
    }
}
