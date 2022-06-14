<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class YearGrades extends Model
{
    protected $table = 'year_grades';

       protected $fillable = [
        'student_id', 'user_id', 'degrees','academic_id','course_id','year','semester','subject_id','record_updated_by','created_at','updated_at','updated_by_ip','created_by_ip','created_by_user','updated_by_user','record_status','table_name','branch_id'
    ];


   
}
