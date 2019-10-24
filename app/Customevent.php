<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Customevent\CustomeventFilters;

class Customevent extends Model
{

    protected $fillable = [];
    protected $perPage = 50;

    public function getInitiator () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter(Builder $builder, Request $request, array $filters = []) { // https://coursehunters.net/course/filtry-v-laravel
        return (new CustomeventFilters($request))->add($filters)->filter($builder);
    }

}
