<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class AccessFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        return $builder;
    }
}