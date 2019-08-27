<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function getMaster () {
        return $this->belongsTo(User::class, 'master_user_id');
    }

    public function getSlave () {
        return $this->belongsTo(User::class, 'slave_user_id');
    }
}
