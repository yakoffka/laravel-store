<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use Illuminate\Support\Facades\Schema; // https://laravel-news.com/laravel-5-4-key-too-long-error part 1/2

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Blade::component
        Blade::component('components.alert', 'alert');

        // Blade::include
        Blade::include('includes.input', 'input');
        Blade::include('includes.textarea', 'textarea');
        Blade::include('includes.select', 'select');
        Blade::include('includes.inputImage', 'inpImage');
        Blade::include('includes.tablePermissions', 'tablePermissions');
        Blade::include('includes.modalChangeItem', 'modalChangeItem');
        Blade::include('includes.addToCart', 'addToCart');
        Blade::include('includes.select-status-order', 'selectStatusOrder'); // depricated
        Blade::include('includes.modal-select', 'modalSelect');
        Blade::include('includes.modal-message', 'modalMessage');
        Blade::include('includes.modal-confirm-destroy', 'modalConfirmDestroy');
        Blade::include('includes.carousel', 'carousel');
        Blade::include('includes.listImage', 'listImage');
        Blade::include('includes.modalChangeImage', 'modalChangeImage');
        Blade::include('includes.modalForm', 'modalForm');
        Blade::include('includes.modal-textarea', 'modalTextarea');

        Schema::defaultStringLength(191); // https://laravel-news.com/laravel-5-4-key-too-long-error part 2/2
    }
}
