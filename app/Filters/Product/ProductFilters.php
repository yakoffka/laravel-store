<?php

namespace App\Filters\Product;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\FiltersAbstract;

// use App\Filters\Product\AccessFilter;
// use App\Filters\Product\ManufacturerFilter;

use App\Filters\Product\{AccessFilter, ManufacturerFilter, VisibleFilter, CategoryFilter};

class ProductFilters extends FiltersAbstract
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


    protected $filters = [
        // 'access' => AccessFilter::class,
        'manufacturers' => ManufacturerFilter::class,
        // 'visible' => VisibleFilter::class,
        'categories' => CategoryFilter::class,
        // 'category_id'
        // 'materials'
        // 'year_manufacture'
        // 'price'
        // 'added_by_user_id'
        // 'edited_by_user_id'
];

}