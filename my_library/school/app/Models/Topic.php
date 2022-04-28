<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Topic extends Model
{
    use HasFactory,SoftDeletes,HasSlug;

    protected $table = "topics";
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'semester_id',
        'multi_branch',
        'branch_id',
        'slug',
        'created_method',
        'subject_id',
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

    public function scopeBranch($query)
    {
        return $query->where('branch_id', Auth::user()->branch_id)->orWhere('multi_branch',1);
    }

    public function subTopics()
    {
        return $this->hasMany(Topic::class,'parent_id');
    }

    public function parentTopic()
    {
        return $this->belongsTo(Topic::class,'parent_id','id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lesson()
    {
        return $this->hasOne(Lesson::class);
    }

}
