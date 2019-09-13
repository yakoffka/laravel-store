<?php

namespace App\Filters\Product;

use Illuminate\Http\Request;
use App\Filters\FiltersAbstract;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Product\{AccessFilter, ManufacturerFilter, VisibleFilter, CategoryFilter};

class ProductFilters extends FiltersAbstract
{
    protected $filters = [
        // 'access' => AccessFilter::class,
        'manufacturers' => ManufacturerFilter::class,
        'visible' => VisibleFilter::class,
        'categories' => CategoryFilter::class,
        // 'category_id'
        // 'materials'
        // 'year_manufacture'
        // 'price'
        // 'added_by_user_id'
        // 'edited_by_user_id'
    ];
}