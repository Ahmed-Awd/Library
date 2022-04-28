<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rahmasa\FillRecord\FillRecord;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Track extends Model
{
    use HasFactory,SoftDeletes,HasSlug, FillRecord;

    protected $table = "university_tracks";

    protected $fillable = [
        'name',
        'slug'
    ];

    public function getSlugOptions(): SlugOptions
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

    public function levels()
    {
        return $this->belongsToMany(Level::class,'university_track_levels');
    }

    public function levelsOfProgram($program)
    {
        return $this->levels()->where('program_id','=', $program->id);
    }

    public function programSettings()
    {
        return $this->belongsToMany(Program::class,'university_settings')->withPivot(['settings_data']);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'university_subjects');
    }

    public function semsterSubjects($program,$semester)
    {
        return $this->subjects()->where('program_id','=', $program->id)->where('semester',$semester->id);
    }

}
