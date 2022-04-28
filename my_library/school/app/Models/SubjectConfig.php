<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rahmasa\FillRecord\FillRecord;

class SubjectConfig extends Model
{
    use HasFactory, FillRecord;

    protected $table = "university_subject_configs";
    protected $fillable = [
        'semester_id',
        'total',
        'passing_grade',
        'subject_id',
        'is_published',
    ];
    public function columns()
    {
        return $this->hasMany(SubjectColumn::class,'config_id');
    }

}
