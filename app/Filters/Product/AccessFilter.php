<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class AccessFilter extends FilterAbstract
{
    // protected $filters = [
    //     'manufacturer'
    //     'category_id'
    //     'visible'
    //     'materials'
    //     'year_manufacture'
    //     'price'
    //     'added_by_user_id'
    //     'edited_by_user_id'
    // ];

    public function filter(Builder $builder, $value)
    {
        return $builder;
    }

}