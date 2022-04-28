<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rahmasa\FillRecord\FillRecord;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Feedback extends Model
{
    use HasFactory,SoftDeletes,HasSlug, FillRecord;

    protected $table = "feedbacks";
    protected $fillable = [
        'title',
        'user_id',
        'branch_id',
        'subject',
        'slug',
        'new',
        'description',
    ];

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

    public function files()
    {
        return $this->hasMany(FeedbackFile::class);
    }

}
