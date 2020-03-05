<?php

namespace App\Providers;

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
            Category::with(['parent', 'children'])
                ->get()
                ->where('parent.id', '=', 1)
                ->where('id', '>', 1)
                ->filter(static function ($value, $key) {
                    if ( !config('settings.show_empty_category') ) {
                        return $value->hasDescendant() && $value->isPublish();
                    }
                    return $value->isPublish();
                })
                ->sortBy('sort_order')
        );
    }
}
