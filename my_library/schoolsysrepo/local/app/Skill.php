<?php


namespace App;


use App\Scopes\DeleteScope;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchScope;

class Skill extends Model
{
    protected $table = 'skills';
    protected $fillable = ['skill_title', 'course_id', 'subject_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }

}