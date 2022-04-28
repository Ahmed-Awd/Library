<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\True_;
use Rahmasa\FillRecord\FillRecord;

class AcademicSemester extends Model
{
    use HasFactory, FillRecord;
    protected $table = 'academics_semesters';
    protected $fillable = [
        'sem_num',
        'sem_start_date',
        'sem_end_date',
        'academic_id'
    ];
    public function academicYear()
    {
        return $this->belongsTo(Academic::class);
    }

    public function universitySubjects()
    {
        return $this->belongsToMany(Subject::class,'university_subjects','semester_id');
    }

    public function schoolSubjects()
    {
        return $this->belongsToMany(Subject::class,'course_subjects','semester_id');
    }

    public function courseSubject() {
        return $this->hasMany(CourseSubject::Class, 'semester_id');
    }

    public function defaultSemester()
    {
        $now = date("Y-m-d h:i:sa");
        return $this->where('sem_start_date','<',$now)->where('sem_end_date','>',$now)->first()->id;
    }

}
