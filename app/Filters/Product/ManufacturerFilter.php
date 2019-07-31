<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;
use App\Manufacturer;

class ManufacturerFilter extends FilterAbstract
{
    public function filter(Builder $builder, $value)
    {
        // dd($value);
        return $builder->whereIn('manufacturer_id', $value);
    }
}