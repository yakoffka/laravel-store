<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\Category;

class NavigationComposer
{

    public function compose (View $view)
    {
        $categories = Category::all()->where('parent_id', '=', null)->sortBy('sort_order');
        return $view->with('categories', $categories);
    }
}