<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TimingSet extends Model
{
    use HasFactory,SoftDeletes,HasSlug;
    protected $table = "timingset";
    protected $fillable = [
        'name',
        'slug',
        'rest_days',
        'description'
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
    public function timingsetdetails () {
        return $this->hasMany(TimingsetDetail::Class,'timingset_id');
    }

}
