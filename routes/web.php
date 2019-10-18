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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return redirect( route('home') );
// });
// Route::get('/', function () {
//     return redirect( route('mainpage') );
// });

/*
    GET '/items' ItemController@index
    GET '/items/create' ItemController@create
    GET '/items/{id}' ItemController@show
    POST '/items' ItemController@store
    GET '/items/edit/{id}' ItemController@edit
    PATCH '/items/{id}' ItemController@update
    DELETE '/items/{id}' ItemController@destroy

    Regular Expression Constraints && Generating URLs To Named Routes:
    Route::get('/items/{id}', 'ItemController@show')->where(['id' => '[0-9]+'])->name('itemsShow');
    $url = route('itemsShow', ['id' => 1]);
*/

Auth::routes();

Route::get('/', 'HomeController@home')->name('home');
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');




/* products*/
    Route::get      ('/products',               'ProductsController@index'      )->name('products.index');
    Route::get      ('/products/create',        'ProductsController@create'     )->name('products.create');
    Route::get      ('/products/{product}',     'ProductsController@show'       )->name('products.show');
    Route::post     ('/products',               'ProductsController@store'      )->name('products.store');
    Route::get      ('/products/{product}/edit','ProductsController@edit'       )->name('products.edit');
    Route::patch    ('/products/{product}',     'ProductsController@update'     )->name('products.update');
    Route::delete   ('/products/{product}',     'ProductsController@destroy'    )->name('products.destroy');
    // filter
    // Route::get('products.filter', 'ProductsController@filter')->name('products.filter');
    // rewatermark
    Route::get      ('admin/products/rewatermark',   'ProductsController@rewatermark')->name('products.rewatermark')->middleware('auth');
    Route::get      ('admin/products/{product}/copy','ProductsController@copy'       )->name('products.copy')->middleware('auth');

    Route::get      ('admin/products',          'ProductsController@adminIndex' )->name('products.adminindex'  )->middleware('auth');
    Route::get      ('admin/products/{product}','ProductsController@adminShow'  )->name('products.adminshow'   )->middleware('auth');
    // Route::get      ('admin/products/{product}','ProductsController@show'       )->name('products.adminshow'   )->middleware('auth');
    Route::post     ('admin/products/massupdate', 'ProductsController@massupdate')->name('products.massupdate'  )->middleware('auth');


/* comments*/
Route::get('/comments', 'ProductCommentsController@index');
Route::get('/comments/create', 'ProductCommentsController@create');
Route::get('/comments/{comment}', 'ProductCommentsController@show');
Route::post('/products/{product}/comments', 'ProductCommentsController@store');
Route::get('/comments/edit/{comment}', 'ProductCommentsController@edit');
Route::patch('/comments/{comment}', 'ProductCommentsController@update');
Route::delete('/comments/{comment}', 'ProductCommentsController@destroy')->name('comments.destroy');

/* users*/
Route::get('/users', 'UsersController@index')->name('users.index');
// Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
// Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/edit/{user}', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');

/* roles*/
Route::get('/roles', 'RolesController@index')->name('roles.index');
Route::get('/roles/create', 'RolesController@create')->name('roles.create');
Route::get('/roles/{role}', 'RolesController@show')->name('roles.show');
Route::post('/roles', 'RolesController@store')->name('roles.store');
Route::get('/roles/edit/{role}', 'RolesController@edit')->name('roles.edit');
Route::patch('/roles/{role}', 'RolesController@update')->name('roles.update');
Route::delete('/roles/{role}', 'RolesController@destroy')->name('roles.destroy');


/* categories*/
    // POST       categories                 categories.store    CategoryController@store    web,auth                                                                                                               |
    // GET|HEAD   categories                 categories.index    CategoryController@index    web                                                                                                                    |
    // GET|HEAD   categories/create          categories.create   CategoryController@create   web,auth                                                                                                               |
    // DELETE     categories/{category}      categories.destroy  CategoryController@destroy  web,auth                                                                                                               |
    // GET|HEAD   categories/{category}      categories.show     CategoryController@show     web                                                                                                                    |
    // PUT|PATCH  categories/{category}      categories.update   CategoryController@update   web,auth                                                                                                               |
    // GET|HEAD   categories/{category}/edit categories.edit     CategoryController@edit     web,auth                                                                                                               |
    Route::resource('categories', 'CategoryController');
    Route::get   ('admin/categories',                       'CategoryController@adminIndex')->name('categories.adminindex'  )->middleware('auth');
    Route::get   ('admin/categories/{category}',            'CategoryController@adminShow' )->name('categories.adminshow'   )->middleware('auth');

// cart
Route::get('cart/add/{product}', 'CartController@addToCart')->name('cart.add-item');
Route::get('cart', 'CartController@show')->name('cart.show');
Route::get('cart/confirmation', 'CartController@confirmation')->name('cart.confirmation')->middleware('auth');
Route::patch('cart/change/{product}', 'CartController@changeItem')->name('cart.change-item');
Route::delete('cart/delete/{product}', 'CartController@deleteItem')->name('cart.delete-item');

// order.save
Route::resource('orders', 'OrderController')->except(['edit']);
// Route::resource('orders', 'OrderController')->except(['index']);
// Route::get('/orders/{user?}', 'CategoryController@index')->name('orders.index');


// images
Route::delete('images/{image}', 'ImagesController@destroy')->name('images.destroy');
Route::patch('images/{image}', 'ImagesController@update')->name('images.update');


// search
Route::get('search', 'ProductsController@search')->name('search');



///////////////////////////////////////////////////////////////////////////////
//      admin panel
///////////////////////////////////////////////////////////////////////////////

    // settings
    Route::resource('settings', 'SettingController');


    // events
        // all 
        Route::get('events', 'EventController@index')->name('events.index');
        Route::get('events/{event}', 'EventController@show')->name('events.show');

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
