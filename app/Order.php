<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    // SQLSTATE[HY000]: General error: 1364 Field 'name' doesn't have a default value (SQL: insert into `products` (`updated_at`, `created_at`) values (2019-09-07 12:19:38, 2019-09-07 12:19:38))
    // public function __construct()
    // {
    //   $this->perPage = config('custom.orders_paginate');
    // }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function customer() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
