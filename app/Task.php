<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{Taskspriority, Tasksstatus};

class Task extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];


    public function __construct()
    {
      $this->perPage = config('custom.tasks_paginate');
    }
    
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
