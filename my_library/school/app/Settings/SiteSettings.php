<?php


namespace App\Settings;


use phpDocumentor\Reflection\File;
use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public string $site_country;
    public string $background_image;
    public string $currency_symbol;
    public string $current_theme;
    public string $school_vision;
    public string $site_address;
    public string $site_city;
    public string $site_favicon;
    public string $site_logo;
    public string $site_logo_attendance_header;
    public string $site_logo_attendance_header2;
    public string $site_state;
    public string $site_title;
    public string $site_zipcode;
    public string $system_timezone;

    public static function group(): string
    {
        return 'site';
    }
}
