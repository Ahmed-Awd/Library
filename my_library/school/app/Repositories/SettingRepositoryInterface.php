<?php


namespace App\Repositories;


use App\Settings\SiteSettings;

interface SettingRepositoryInterface
{
    public function showSiteSettings(SiteSettings $settings);

    public function updateSiteSettings($data ,SiteSettings $settings);
}

