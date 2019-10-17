<?php

namespace App\Filters\Action;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class ModelFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        return $builder->whereIn('model', $value);
    }
}