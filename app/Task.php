<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{Taskspriority, Tasksstatus};

class Task extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];


    // Illuminate\Database\QueryException  : SQLSTATE[HY000]: General error: 1364 Field 'name' doesn't have a default value (SQL: insert into `products` (`updated_at`, `created_at`) values (2019-09-05 00:58:39, 2019-09-05 00:58:39))
    // public function __construct()
    // {
    //   $this->perPage = config('custom.tasks_paginate');
    // }
    
    public function getMaster () {
        return $this->belongsTo(User::class, 'master_user_id');
    }

    public function getSlave () {
        return $this->belongsTo(User::class, 'slave_user_id');
    }

    public function getPriority () {
        return $this->belongsTo(Taskspriority::class, 'taskspriority_id');
    }
    
    public function getStatus () {
        return $this->belongsTo(Tasksstatus::class, 'tasksstatus_id');
    }
}
