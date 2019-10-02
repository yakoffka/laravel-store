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
    protected $perPage = 50;


    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    // FatalErrorException (E_UNKNOWN) Constant expression contains invalid operations
    // protected $perPage = config('custom.actions_paginate');

    // public function __construct()
    // {
    //   $this->perPage = config('custom.actions_paginate');
    // }

    public function getInitiator () {
        return $this->belongsTo(User::class, 'user_id');
    }

}
