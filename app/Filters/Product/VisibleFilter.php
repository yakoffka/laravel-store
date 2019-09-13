<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class VisibleFilter extends FilterAbstract
{

    /**
     * Mappings for database values.
     *
     * @return array
     *
     */
    public function mappings()
    {
        return [
            'visible' => true,
            'invisible' => false,
        ];
    }

    /**
     * Filter by visible
     * 
     * @param string $value
     * 
     * @return Illuminate\Database\Eloquent\Builder
     * 
     */
    public function filter(Builder $builder, $value)
    {

        $value = $this->resolveFilterValue($value);
        // dd($value);

        if ( auth()->user() and auth()->user()->can('create_products') ) {
            return $builder->where('visible', true);
        } else {
            // return $builder->where('visible', false);
            return null;
        }

    }
}