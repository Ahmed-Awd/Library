<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rahmasa\FillRecord\FillRecord;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Instruction extends Model
{
    use HasFactory,SoftDeletes,HasSlug, FillRecord;

    protected $table = "instructions";
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new BranchScope);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(50);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }


}
