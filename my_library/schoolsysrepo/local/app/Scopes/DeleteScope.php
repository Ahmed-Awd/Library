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

class DeleteScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        return $builder->where($model->getTable().".record_status", '!=', 3);
    }
}