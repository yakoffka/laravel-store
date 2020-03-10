<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
    GET '/items' ItemController@index
    GET '/items/create' ItemController@create
    GET '/items/{id}' ItemController@show
    POST '/items' ItemController@store
    GET '/items/edit/{id}' ItemController@edit
    PATCH '/items/{id}' ItemController@update
    DELETE '/items/{id}' ItemController@destroy
*/


if ( !empty(config('settings.display_registration')) ) {
    Auth::routes();
} else {
    Auth::routes(['register' => false]);
}

/* main */
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');
    Route::post('/contact_us', 'HomeController@contactUs')->name('home.contact_us');

/* products */
    Route::resource ('/products',               'ProductsController');
    Route::get      ('admin/products/rewatermark',   'ProductsController@rewatermark')->name('products.rewatermark')->middleware('auth');
    Route::get      ('admin/products/{product}/copy','ProductsController@copy'       )->name('products.copy')->middleware('auth');
    Route::get      ('admin/products',               'ProductsController@adminIndex' )->name('products.adminindex'  )->middleware('auth');
    Route::get      ('admin/products/{product}',     'ProductsController@adminShow'  )->name('products.adminshow'   )->middleware('auth');
    Route::post     ('admin/products/massupdate',    'ProductsController@massupdate' )->name('products.massupdate'  )->middleware('auth');
    Route::get      ('search',                       'ProductsController@search'     )->name('search');

/* comments */
    Route::post('/products/{product}/comments', 'ProductCommentsController@store')->name('comments.store');
    Route::patch('/comments/{comment}', 'ProductCommentsController@update')->name('comments.update');
    Route::delete('/comments/{comment}', 'ProductCommentsController@destroy')->name('comments.destroy');

/* users */
    Route::resource('/users', 'UsersController')->except(['store', 'create']);
    Route::patch('/users/{user}/password-change', 'UsersController@passwordChange')->name('users.password-change');

/* roles */
    Route::resource('/roles', 'RolesController');

/* categories */
    Route::resource('categories', 'CategoryController');
    Route::get('admin/categories',            'CategoryController@adminIndex')->name('categories.adminindex')->middleware('auth');
    Route::get('admin/categories/{category}', 'CategoryController@adminShow' )->name('categories.adminshow' )->middleware('auth');

/* cart */
    Route::get('cart/add/{product}','CartController@addToCart')->name('cart.add-item');
    Route::get('cart',              'CartController@show')->name('cart.show');
    Route::get('cart/confirmation', 'CartController@confirmation')->name('cart.confirmation')->middleware('auth');
    Route::patch('cart/change/{product}','CartController@changeItem')->name('cart.change-item');
    Route::delete('cart/delete/{product}','CartController@deleteItem')->name('cart.delete-item');

// orders
    Route::resource('orders',    'OrderController')->except(['edit']);
    Route::get('admin/orders',        'OrderController@adminIndex')->name('orders.adminindex'  )->middleware('auth');
    Route::get('admin/orders/{order}','OrderController@adminShow' )->name('orders.adminshow'   )->middleware('auth');

// images
    Route::delete('images/{image}','ImagesController@destroy')->name('images.destroy');
    Route::patch('images/{image}', 'ImagesController@update')->name('images.update');





///////////////////////////////////////////////////////////////////////////////
//      admin panel
///////////////////////////////////////////////////////////////////////////////

    // settings
    Route::resource('settings', 'SettingController');

    // customevents
        Route::get('customevents', 'CustomeventController@index')->name('customevents.index');
        Route::get('customevents/{customevent}', 'CustomeventController@show')->name('customevents.show');

    // manufacturers
        Route::resource('manufacturers', 'ManufacturerController')->middleware('auth');

    // Tasks and Directive
        // tasks
        Route::resource('tasks', 'TaskController');
        // directives
        Route::get('users/alldirectives', 'TaskController@directives')->name('alldirectives.index');
        Route::get('directives', 'TaskController@directives')->name('directives.index');
        Route::get('directives/{task}', 'TaskController@directive')->name('directives.show');


    Route::get('/clear', function() {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        // Artisan::call('config:cache'); // erased session()->flash!
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        // Artisan::call('route:cache');
        Artisan::call('queue:restart');
        // composer dump-autoload
        session()->flash('message', 'Application cache is almost cleared .. (without "Artisan::call(\'config:cache\');"');
        return back();
    })->name('clear');




    Route::group(['prefix' => 'lfm', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
