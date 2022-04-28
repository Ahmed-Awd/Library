<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\CategoryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rahmasa\FillRecord\FillRecord;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Subject extends Model
{
    use HasFactory,SoftDeletes,HasSlug, FillRecord;

    protected $table = "subjects";
    protected $fillable = [
        'title',
        'slug',
        'is_lab',
        'category_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CategoryScope);
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

    public function config() {
        return $this->hasOne(SubjectConfig::Class,'subject_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function courseSubject()
    {
        return $this->hasMany(CourseSubject::class);
    }
    public function studentSubject()
    {
        return $this->hasMany(StudentSubject::class);
    }
    public function staffSubjects()
    {
        return $this->belongsToMany(User::class,'staff_subjects')->withPivot(['class_id','semester_id']);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }


    public function semesterTopics($semester)
    {
        $new = new AcademicSemester();
        $semester == null ? $current = $new->defaultSemester() : $current = $semester->id;
        return $this->topics()->Branch()->where('semester_id','=', $current)->where('parent_id',null);
    }

}
