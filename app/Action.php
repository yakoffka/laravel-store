<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{

    // public $timestamps = false;

    protected $guarded = [
        'id',
        'created_at',
    ];


    public function __construct()
    {
      $this->perPage = config('custom.actions_paginate');
    }

    public function getInitiator () {
        return $this->belongsTo(User::class, 'user_id');
    }

}
