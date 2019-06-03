<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    // public function category() {
    //     return $this->hasOneThrough('App\Category', 'App\CategoryProduct', 'id', 'category_id');
    // }
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'edited_by_user_id');
    }
    
}
