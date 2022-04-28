<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimingsetDetail extends Model
{
    use HasFactory;
    protected $table = "timingsetdetails";

    public function timetables(){
        return $this->hasMany(Timetable::class,'timingset_details_id');
    }

    public function timingset(){
        return $this->hasMany(TimingSet::class);
    }
}
