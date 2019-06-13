<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;
use App\Manufacturer;

class ManufacturerFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        // return $builder->where('manufacturer', $value);
        $manufacturer = Manufacturer::all()->firstWhere('name', '=', $value);
        // dd($manufacturer);

        // return $builder->where('manufacturer_id', $manufacturer->id);

        if ($manufacturer) {
            return $builder->where('manufacturer_id', $manufacturer->id);
        // } else {
        //     // return $builder;
        //     return null;
        }
        
    }
}