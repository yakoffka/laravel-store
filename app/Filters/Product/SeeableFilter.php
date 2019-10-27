<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class SeeableFilter extends FilterAbstract
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
            'seeable' => true,
            'inseeable' => false,
        ];
    }

    /**
     * Filter by seeable
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
            return $builder->where('seeable', true);
        } else {
            // return $builder->where('seeable', false);
            return null;
        }

    }
}