<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Academic extends Model
{
    use HasFactory , HasSlug;
    protected  $table = "academics";
    protected $fillable = [
        'academic_year_title',
        'academic_start_date',
        'academic_end_date',
        'show_in_list',
        'slug'
    ];
    public function academicSemesters() {
        return $this->hasMany(AcademicSemester::class, 'academic_id');
    }
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50);
    }
    public function defaultYear()
    {
        $now = date("Y-m-d h:i:sa");
        return $this->where('academic_start_date','<',$now)->where('academic_end_date','>',$now)->select(['id', 'academic_year_title'])->first();
    }
    public function defaultSemesters()
    {
        $academic_id = $this->defaultYear()->id;
        return AcademicSemester::where('academic_id',$academic_id)->get();
    }
}
