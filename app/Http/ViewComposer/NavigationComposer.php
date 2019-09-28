<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\Category;

class NavigationComposer
{

    public function compose (View $view)
    {
        $categories = Category::all()
            ->where('id', '<>', 1)
            ->where('parent_id', '=', 1)
            ->where('visible', '=', true)
            ->where('parent_visible', '=', true) // getParentVisibleAttribute
            ->sortBy('sort_order');
        return $view->with('categories', $categories);
    }
}