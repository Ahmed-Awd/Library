<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Semister;
use DB;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;
use App\Scopes\CategoryScope;

class Course extends Model
{

    /**
     * Get the semisters for the selected course
     * @return [type] [description]
     */
    public function semisters()
    {
    	return $this->hasMany('App\CourseSemister');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new CategoryScope);
    }
    
    public function allotedFeeCategories($academicCourseId)
    {
        if($academicCourseId)
        {
            return DB::table('feecategories_academiccourses')
            ->where('academic_course_id', '=',$academicCourseId)
            ->get();
        }
        return array();
    }


    public static function getCourses($parentId = 0)
    {
        return Course::where('parent_id','=', $parentId)->get();
    }

    public function getCourseDueration($course_id)
    {
        return Course::where('id','=',$course_id)->select('course_dueration')->get();
    }
    public function getParentForChild()
    {
        return Course::where('id','=', $this->parent_id)->get(['course_title']);
    }
    public static function getCourseRecord($courseId )
    {
        return Course::where('id','=', $courseId)->get()->first();
    }
    public function getParentCourseTitle($id)
    {
        return $this->where('id',$this->find($id)->parent_id)->first(['course_title'])->course_title;
    }

}
