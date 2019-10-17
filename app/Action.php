<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Action\ActionFilters;

class Action extends Model
{

    protected $fillable = [];
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

    public function scopeFilter(Builder $builder, Request $request, array $filters = []) { // https://coursehunters.net/course/filtry-v-laravel
        return (new ActionFilters($request))->add($filters)->filter($builder);
    }

}
