<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Category;
use App\Http\ViewComposer\{FilterManufacturerComposer, FilterCustomeventsComposer};
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // filters
        view()->composer('layouts.partials.filter-manufacturer', FilterManufacturerComposer::class);
        view()->composer('dashboard.adminpanel.partials.filters.filter-customevent', FilterCustomeventsComposer::class);


        // Sharing data categories for all views
        View::share('globalCategories',
            Category::with(['parent', 'subcategories'])
                ->get()
                ->where('parent.id', '=', 1)
                ->where('id', '>', 1)
                ->filter(static function (Category $value) {
                    if (!config('settings.show_empty_category')) {
                        return $value->hasDescendant() && $value->isPublish();
                    }
                    return $value->isPublish();
                })
                ->sortBy('sort_order')
        );

        View::share('sharedRecursiveCategories',
            Cache::remember('sharedRecursiveCategories', 3600, function () {
                return Category::where('parent_id', '=', 1)
                    ->with('recursiveSubcategories')
                    ->withCount('subcategories')
                    ->withCount('products')
                    ->get();
            })
        );

        View::share('sharedFlatCategories',
            Cache::remember('sharedFlatCategories', 3600, function () {
                return Category::withCount('subcategories')
                    ->withCount('products')
                    ->with('subcategories')
                    ->get();
            })
        );
    }
}
