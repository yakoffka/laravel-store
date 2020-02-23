<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\Category;

class FilterCategoryComposer
{
    public function compose (View $view): View
    {
        $categories = Category::with(['parent', 'children'])
            ->get()
            ->where('parent.id', '=', 1)
            ->where('parent.seeable', '=', 'on')
            ->where('id', '>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasDescendant() && $value->fullSeeable();
            })
            ->sortBy('sort_order');

        return $view->with('categories', $categories);
    }
}
