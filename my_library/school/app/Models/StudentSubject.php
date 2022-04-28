<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'subject_id',
        'semester_id'
    ];

    public function subject(){
        return $this->belongsTo(Subject::Class);
    }
}
