<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class AcademicSemester extends Model
{
    protected $table="academics_semesters";
    public $timestamps=false;

    public function getCurrentSemeterOfAcademicYear($academic_year)
    {
           $nowDate=date("Y-m-d h:i:sa");
           return $this->where('academic_id',$academic_year)->where('sem_start_date','<',$nowDate)->where('sem_end_date','>',$nowDate)->first(['sem_num']);
    }
    public function getSemetersOfAcademicYear($academic_year)
    {
           $nowDate=date("Y-m-d h:i:sa");
           return $this->where('academic_id',$academic_year)->get(['sem_num']);
    }
}