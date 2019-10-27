<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\Category;

class FilterCategoryComposer
{

    public function compose (View $view)
    {
        $categories = Category::all()
            ->where('seeable', '=', 'on')
            ->where('parent_seeable', '=', 'on'); // getParentSeeableAttribute
        return $view->with('categories', $categories);
    }
}