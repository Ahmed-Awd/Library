<?php


namespace App\Settings;


use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public string $google_analytics;
    public string $meta_description;
    public string $meta_keywords;


    public static function group(): string
    {
        return 'seo';
    }

}
