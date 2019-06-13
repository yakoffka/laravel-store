<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class VisibleFilter extends FilterAbstract
{

    public function mappings()
    {
        return [
            'visible' => true,
            'invisible' => false,
        ];
    }

    public function filter(Builder $builder, $value)
    {
        $value = $this->resolveFilterValue($value);

        if ( $value === null ) {
            return $builder;
        }

        return $builder->where('visible', $value);
    }
}