<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'title',
        'description',
        'imagepath',
        'edited_by_user_id',
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function countProducts() {
        return $this->hasMany(Product::class)->count();
    }


    public function creator() {
        return $this->belongsTo(User::class, 'added_by_user_id')->withDefault([
            'name' => 'no author'
        ]);
    }

    public function editor() {
        // return $this->belongsTo(User::class, 'edited_by_user_id');
        return $this->belongsTo(User::class, 'edited_by_user_id')->withDefault([
            'name' => 'no editor'
        ]);
    }

}
