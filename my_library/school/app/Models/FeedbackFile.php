<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackFile extends Model
{
    use HasFactory;

    protected $table = "feedback_files";
    public $timestamps = false;

    protected $fillable = [
        'file_name',
        'feedback_id',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
}
