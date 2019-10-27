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
            ->where('seeable', '=', 'on')
            ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
            ->sortBy('sort_order');
        return $view->with('categories', $categories);
    }
}