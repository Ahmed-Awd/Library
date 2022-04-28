<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSubject extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'course_id',
        'subject_id',
        'semester_id',
        'branch_id',
    ];
    public function semester() {
        return $this->belongsTo(AcademicSemester::Class);
    }

    public function subject() {
        return $this->belongsTo(Subject::Class);
    }
}
