<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Product\ProductFilters;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            // 'products.id' => 10,
            'products.name' => 10,
            'products.description' => 10,
        ],
    ];
    
    protected $guarded = [];

    public function __construct() {
      $this->perPage = config('custom.products_paginate');
    }
    // при раскомментировании __construct сидирование заканчивается ошибкой:
    // Illuminate\Database\QueryException  : SQLSTATE[HY000]: General error: 1364 Field 'name' doesn't have a default value (SQL: insert into `products` (`updated_at`, `created_at`) values (2019-09-05 00:58:39, 2019-09-05 00:58:39))

    public function comments() {
        // return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
        return $this->hasMany(Comment::class)->orderBy('created_at');
    }

    // public function category() {
    //     return $this->hasOneThrough('App\Category', 'App\CategoryProduct', 'id', 'category_id');
    // }
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    public function editor() {
        // return $this->belongsTo(User::class, 'edited_by_user_id');
        return $this->belongsTo(User::class, 'edited_by_user_id')->withDefault([
            'name' => 'NO Author'
        ]);;
    }

    public function manufacturer() {
        return $this->belongsTo(Manufacturer::class);
    }

    public function scopeFilter(Builder $builder, /*Request */$request, array $filters = []) { // https://coursehunters.net/course/filtry-v-laravel
        return (new ProductFilters($request))->add($filters)->filter($builder);
    }
    
    public function images() {
        return $this->hasMany(Image::class)->orderBy('sort_order');
    }

}
