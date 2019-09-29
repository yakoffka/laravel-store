<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class CategoryFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        return $builder->whereIn('category_id', $value);
        // attempt to use accessorgetCategoryVisibleAttribute
        // SQLSTATE[42S22]: Column not found: 1054 Unknown column 'category_visible' in 'where clause' (SQL: select count(*) as aggregate from `products` where `visible` = 1 and `category_id` in (12, 13, 14, 15, 16, 17) and `category_visible` = 1)
        // return $builder->whereIn('category_id', $value)->where('category_visible', '=', true);
    }
}