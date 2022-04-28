<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use App\Repositories\SettingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class SettingController extends Controller
{


    private SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->settingRepository->get();
        return response()->json(['settings' => $data]);
    }


    public function show(Setting $setting): JsonResponse
    {
        $setting = $this->settingRepository->show($setting);
        return response()->json(['setting' => $setting]);
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $settings = $validated['settings'];
        foreach ($settings as $key => $value) {
            $this->settingRepository->update($key, $value);
        }
        return response()->json(['message' => Lang::get('messages.general_settings.update')]);
    }

    public function contactInfo(): JsonResponse
    {
        $info = $this->settingRepository->getContactInfo();
        return response()->json(['data' => $info]);
    }

    public function mobileInfo(): JsonResponse
    {
        $info = $this->settingRepository->getMobileInfo();
        return response()->json(['data' => $info]);
    }
}
