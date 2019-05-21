<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];
    // protected $fillable = ['comment_string'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
