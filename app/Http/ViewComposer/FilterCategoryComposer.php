<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\Category;

class FilterCategoryComposer
{

    public function compose (View $view)
    {
        $categories = Category::all()
            ->where('visible', '=', true)
            ->where('parent_visible', '=', true); // getParentVisibleAttribute
        return $view->with('categories', $categories);
    }
}