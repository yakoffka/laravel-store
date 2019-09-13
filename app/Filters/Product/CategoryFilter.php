<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;
// use App\Category;

class CategoryFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        return $builder->whereIn('category_id', $value);
    }
}