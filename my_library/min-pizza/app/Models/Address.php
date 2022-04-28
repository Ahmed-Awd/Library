<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $appends = ['translated_type'];
    public static array $langs = ['en','sv'];
    protected $types = [
        "home" => [
            "en" => "home",
            "sv" => "Hem"
        ],
        "work" => [
            "en" => "work",
            "sv" => "verk"
        ],
        "other" => [
            "en" => "other",
            "sv" => "Övrig"
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTranslatedTypeAttribute(): string
    {
        $current = app()->getLocale();
        $type = $this->type;
        if (in_array($current, self::$langs)) {
            return $this->types[$type][$current];
        }
        return $this->types[$type]['en'];
    }
}
