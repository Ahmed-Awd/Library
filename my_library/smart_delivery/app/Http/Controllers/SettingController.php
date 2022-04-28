<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use App\Repositories\SettingRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

class SettingController extends Controller
{

    private SettingRepositoryInterface $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function index()
    {
        $data['settings'] = $this->settingRepository->get();
        foreach ($data['settings'] as $setting) {
            if ($setting['is_price']) {
                $setting['value'] = $setting['value'] / 100;
            }
        }
        return Inertia::render('Settings/Index', $data);
    }

    public function update(UpdateSettingRequest $request)
    {
        $data = $request->validated();

        foreach ($data['settings'] as $setting) {
            if ($setting['is_price']) {
                $setting['value'] = $setting['value'] * 100;
            }
            if ($setting['value'] == null) {
                $setting['value'] = "";
            }
            settings()->set($setting['key'], $setting['value']);
        }
        session()->flash('flash.banner',  Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('settings.index');
    }
}
