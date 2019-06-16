<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Category;
use App\Http\ViewComposer\{NavigationComposer, FilterManufacturerComposer};

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.partials.nav', NavigationComposer::class);
        view()->composer('layouts.partials.filter-manufacturer', FilterManufacturerComposer::class);
    }
}
