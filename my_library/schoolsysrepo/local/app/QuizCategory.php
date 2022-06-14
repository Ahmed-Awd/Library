<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class QuizCategory extends Model
{
    protected $table = "quizcategories";

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new BranchScope);
    }

    public static function getRecordWithSlug($slug)
    {
        return QuizCategory::where('slug', '=', $slug)->first();
    }

    /**
     * Lists the list of quizes related to the selected category
     * @return [type] [description]
     */
    public function quizzes()
    {
        return $this->getQuizzes()
        ->where('start_date','<=',date('Y-m-d H:i:s'))
        ->where('end_date','>=',date('Y-m-d H:i:s'))
        ->where('total_questions','>','0')
        ->where('applicable_to_specific', '=', 1)
        ->where('type','=','online')
        ->where('record_status','!=','3')
        ->get();


    }

    public function getQuizzes()
    {
        return $this->hasMany('App\Quiz', 'category_id');

    }
}
