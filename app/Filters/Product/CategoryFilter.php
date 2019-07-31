<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;
use App\Category;

class CategoryFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        // dd($value);

        // $category = Category::all()->firstWhere('slug', '=', $value);
        // // dd($category);
        // // return $builder->where('slug', $category->id);
        // if ($category) {
        //     return $builder->where('category_id', $category->id);
        // }
        return $builder->whereIn('category_id', $value);
    }
}