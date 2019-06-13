<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;
use App\Product;

class ManufacturerFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        return $builder->where('manufacturer', $value);


        // // $manufacturer = Product::toArray()->pluck('manufacturer');
        // $manufacturer = Product::all()->data_get('manufacturer');
        // dd($manufacturer);


        // if ( Arr::has($value) ) {
        //     return $builder->where('manufacturer', $value);
        // } else {
        //     return $builder;
        // }
        
    }
}