<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CategoryScope  implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check()) {
            $category_id = auth()->user()->category_id;
            $builder->where('category_id', '=', $category_id);
        }
    }
}
