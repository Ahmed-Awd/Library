<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "timetable";

    public function timingsetdetails(){
        return $this->belongsTo(TimingsetDetail::Class);
    }
    public function user(){
        return $this->belongsTo(User::Class);
    }
}
