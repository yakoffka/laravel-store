<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;

trait HasChildren
{

    public function scopeParents(Builder $builder)
    {
        $builder->whereNull('parent_id');
    }

}