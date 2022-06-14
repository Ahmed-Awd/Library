<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class CourseSubject extends Model
{
    protected $table = 'course_subject';

    public static function getCourseSavedSubjects($academicId, $courseId, $year, $semister=0 , $branch_id=0)
    {
        return CourseSubject::join('subjects', 'subjects.id','=','course_subject.subject_id')

            ->where('course_subject.course_id', '=', $courseId)
           // ->where('year', '=', $year)

            ->where('academic_id', '=', $academicId)
            ->where('semister', '=', $semister)
            ->where('course_subject.branch_id', '=', $branch_id)
            ->get();
    }

    public static function isStaffAllocatedToCourse($staff_id)
    {
        return CourseSubject::where('staff_id','=',$staff_id)->count();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new BranchScope);
    }
}
