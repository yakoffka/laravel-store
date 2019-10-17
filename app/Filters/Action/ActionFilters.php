<?php

namespace App\Filters\Action;

use App\Filters\FiltersAbstract;
use App\Filters\Action\{ModelFilter, UserFilter};

class ActionFilters extends FiltersAbstract
{
    protected $filters = [
        'models' => ModelFilter::class,
        'types' => TypeFilter::class,
        'users' => UserFilter::class,
    ];
}