<?php

namespace App\Filters\Product;

use Illuminate\Http\Request;
use App\Filters\FiltersAbstract;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Product\{AccessFilter, ManufacturerFilter, PublishFilter, CategoryFilter};

class ProductFilters extends FiltersAbstract
{
    protected $filters = [
        // 'access' => AccessFilter::class,
        'manufacturers' => ManufacturerFilter::class,
        'publish' => PublishFilter::class,
        'categories' => CategoryFilter::class,
        // 'category_id'
        // 'materials'
        // 'date_manufactured'
        // 'price'
        // 'added_by_user_id'
        // 'edited_by_user_id'
    ];
}
