<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Rahmasa\FillRecord\FillRecord;

class SubjectColumn extends Model
{
    use HasFactory, FillRecord;

    protected $table = "university_subject_cols";
    protected $fillable = [
        'name',
        'score',
        'config_id',
    ];

    public function config() {
        return $this->belongsTo(SubjectConfig::Class);
    }

}
