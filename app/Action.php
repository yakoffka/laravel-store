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

    public function getInitiator () {
        return $this->belongsTo(User::class, 'user_id');
    }

}
