<?php
/**
 * Created by PhpStorm.
 * User: ImaginaryKing
 * Date: 3/18/2018
 * Time: 9:06 AM
 */
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BranchScope implements Scope
{


    public function apply(Builder $builder, Model $model)
    {
        if($model->getTable() != 'users'){
            if(Auth::user() && (Auth::user()->role_id !=6 && Auth::user()->role_id !=10)){
                $id = Auth::user()->branch_id;
                return $builder->where($model->getTable().".branch_id", '=', $id);
            }
        }
        if(Session::has('branch_id')){
            return $builder->where($model->getTable().".branch_id", '=', session()->get('branch_id'));
        }

    }
}
