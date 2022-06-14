<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CategoryScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2){
            $id = Auth::user()->category_id;
            return $builder->where($model->getTable().".category_id", '=', $id);
        }
    }
}