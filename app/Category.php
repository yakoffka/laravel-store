<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


use App\Product;
use App\Category;
use App\Traits\HasChildren;
use App\Traits\IsOrderable;

class Category extends Model
{
    use HasChildren, IsOrderable;

    protected $guarded = [
        'id',
    ];


    public function products() 
    {
        return $this->hasMany(Product::class);
    }

    public function parent() 
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
}
