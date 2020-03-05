<?php

namespace App\Filters\Product;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\FilterAbstract;

class PublishFilter extends FilterAbstract
{

    /**
     * Mappings for database values.
     *
     * @return array
     *
     */
    public function mappings(): array
    {
        return [
            'publish' => true,
            'un_publish' => false,
        ];
    }

    /**
     * Filter by publish
     *
     * @param Builder $builder
     * @param string $value
     *
     * @return Builder|null
     */
    public function filter(Builder $builder, $value)
    {

        $value = $this->resolveFilterValue($value);

        if (auth()->user() && auth()->user()->can('create_products')) {
            return $builder->where('publish', true);
        }

        return null;

    }
}
