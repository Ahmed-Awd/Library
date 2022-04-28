<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SettingController extends Controller
{
    public function general(): JsonResponse
    {
        $data = Setting::whatsapp();
        return response()->json(['setting' => $data]);
    }
}
