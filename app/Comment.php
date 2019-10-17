<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];
    // protected $fillable = ['body'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
