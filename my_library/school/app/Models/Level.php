<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Level extends Model
{
    use HasFactory,SoftDeletes,HasSlug;

    protected $table = "university_levels";
    protected $fillable = [
        'name',
        'slug'
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->slugsShouldBeNoLongerThan(50);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class,'university_track_levels');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class,'university_track_levels');
    }

}
