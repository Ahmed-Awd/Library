<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rahmasa\FillRecord\FillRecord;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug, FillRecord;
    protected $fillable = [
        'name',
        'slug'
    ];
    public function getSlugOptions() : SlugOptions {

        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50)
        ->usingSeparator('_');
     }
}
