<?php

namespace App\Filters\Customevent;

use App\Filters\FiltersAbstract;
use App\Filters\Customevent\{ModelFilter, UserFilter};

class CustomeventFilters extends FiltersAbstract
{
    protected $filters = [
        'models' => ModelFilter::class,
        'types' => TypeFilter::class,
        'users' => UserFilter::class,
    ];
}