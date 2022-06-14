<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Scopes\DeleteScope;
use App\Scopes\BranchScope;

class ExamSeries extends Model
{
    protected $table = 'examseries';

    public static function getRecordWithSlug($slug)
    {
        return ExamSeries::where('slug', '=', $slug)->first();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
        static::addGlobalScope(new BranchScope);
    }

    /**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public function itemsList()
    {
        return DB::table('examseries_data')
         ->join('quizzes', 'quizzes.id', '=', 'quiz_id')
         ->select('quizzes.*' )
            ->where('examseries_id', '=', $this->id)->get();
    }
    
}
