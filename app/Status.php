<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
    ];

    private function orders() {
        return $this->hasMany(Order::class);
    }
}
