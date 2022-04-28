<?php

namespace App\Http\Controllers;

use App\Repositories\SettingRepositoryInterface;
use App\Settings\SiteSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }
    public function showSiteSettings(SiteSettings $settings): JsonResponse{
        $this->authorize('show settings');
        $siteSettings = $this->settingRepository->showSiteSettings($settings);
        return response()->json($settings);

    }

    public function updateSiteSettings(Request $request, SiteSettings $settings) : JsonResponse {
        $this->authorize('edit settings');
        $this->settingRepository->updateSiteSettings($request , $settings);
        return response()->json(["message" => "settings updated successfully"]);
    }
}
