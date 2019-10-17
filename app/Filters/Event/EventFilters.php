<?php

namespace App\Filters\Event;

use App\Filters\FiltersAbstract;
use App\Filters\Event\{ModelFilter, UserFilter};

class EventFilters extends FiltersAbstract
{
    protected $filters = [
        'models' => ModelFilter::class,
        'types' => TypeFilter::class,
        'users' => UserFilter::class,
    ];
}