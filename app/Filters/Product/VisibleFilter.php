<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class VisibleFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        $value = ($value === 'all' ? true : false);

        if( $value ) {
            return $builder->where('visible', $value);
        } else {
            return $builder;
        }
        
    }
}