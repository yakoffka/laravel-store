<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Product;
use App\Traits\Category\{HasChildren, IsOrderable};


class Category extends Model
{
    // protected $guarded = [
    //     'id',
    // ];
    protected $fillable = [];

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
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function countChildren() // учесть видимость (свою и родительскую)!
    {
        return $this->hasMany(Category::class, 'parent_id')->count();
    }

    public function countProducts() // учесть видимость (свою и родительскую)!
    {
        return $this->hasMany(Product::class)->count();
    }

    /**
     * Accessor
     * in controller using snake-case: $category->parent_visible!!!
     */
    public function getParentVisibleAttribute()
    {
        return $this->belongsTo(Category::class, 'parent_id')->get()->max('visible');
    }
}
