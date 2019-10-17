<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Event\EventFilters;

class Event extends Model
{

    protected $fillable = [];
    protected $perPage = 50;

    public function getInitiator () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter(Builder $builder, Request $request, array $filters = []) { // https://coursehunters.net/course/filtry-v-laravel
        return (new EventFilters($request))->add($filters)->filter($builder);
    }

}
