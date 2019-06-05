<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Product;
use App\Category;

class Category extends Model
{
    // protected $fillable = [
    //     'name',
    //     'image',
    // ];
    protected $guarded = [
        'id',
    ];

    public function products() {
    return $this->hasMany(Product::class);
    }

    public function parent() {
        // return Category::findOrFail($id);
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
