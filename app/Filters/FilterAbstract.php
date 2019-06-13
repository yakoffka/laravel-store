<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class FilterAbstract
{
    abstract function filter(Builder $builder, $value);

    public function mappings() {
        return [];
    }

    protected function resolveFilterValue($key) {
        return array_get($this->mappings(), $key);
    }
}