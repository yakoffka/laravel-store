<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'cart',
        'status_id',
    ];

    private function status() {
        return $this->belongsTo(Status::class);
    }

    private function customer() {
        return $this->belongsTo(User::class);
    }
}
