<?php


namespace App\Repositories;


use App\Models\Setting;
use App\Settings\SiteSettings;
use Illuminate\Support\Facades\Request;

class SettingRepository implements SettingRepositoryInterface
{

    public function showSiteSettings(SiteSettings $settings){
        return $settings;
    }

    public function updateSiteSettings($data, SiteSettings $settings)
    {
        $settings->site_country = $data->input('site_country');
        $settings->background_image = $data->input('background_image');
        $settings->currency_symbol = $data->input('currency_symbol');
        $settings->current_theme = $data->input('current_theme');
        $settings->school_vision = $data->input('school_vision');
        $settings->site_address = $data->input('site_address');
        $settings->site_city = $data->input('site_city');
        $settings->site_favicon = $data->input('site_favicon');
        $settings->site_logo = $data->input('site_logo');
        $settings->site_logo_attendance_header = $data->input('site_logo_attendance_header');
        $settings->site_logo_attendance_header2 = $data->input('site_logo_attendance_header2');
        $settings->site_state = $data->input('site_state');
        $settings->site_title = $data->input('site_title');
        $settings->site_zipcode = $data->input('site_zipcode');
        $settings->system_timezone = $data->input('system_timezone');

        $settings->save();
    }

}
