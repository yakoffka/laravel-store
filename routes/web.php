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
Route::get('/', function () {
    return redirect( route('products') );
});

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

Route::get('/home', 'HomeController@index')->name('home');




/* products*/
Route::get('/products', 'ProductsController@index')->name('products');
Route::get('/products/create', 'ProductsController@create')->name('productsCreate');
Route::get('/products/{product}', 'ProductsController@show')->name('productsShow');
Route::post('/products', 'ProductsController@store')->name('productsStore');
Route::get('/products/edit/{product}', 'ProductsController@edit')->name('productsEdit');
Route::patch('/products/{product}', 'ProductsController@update')->name('productsUpdate');
Route::delete('/products/{product}', 'ProductsController@destroy')->name('productsDestroy');

/* comments*/
Route::get('/comments', 'ProductCommentsController@index');
Route::get('/comments/create', 'ProductCommentsController@create');
Route::get('/comments/{comment}', 'ProductCommentsController@show');
// Route::post('/comments', 'ProductCommentsController@store');
Route::post('/products/{product}/comments', 'ProductCommentsController@store');
Route::get('/comments/edit/{comment}', 'ProductCommentsController@edit');
Route::patch('/comments/{comment}', 'ProductCommentsController@update');
// Route::delete('/comments/destroy/{comment}', 'ProductCommentsController@destroy');
Route::delete('/comments/{comment}', 'ProductCommentsController@destroy')->name('commentsDestroy');

/* users*/
Route::get('/users', 'UsersController@index')->name('users');
// Route::get('/users/create', 'UsersController@create')->name('usersCreate');
Route::get('/users/{user}', 'UsersController@show')->name('usersShow');
// Route::post('/users', 'UsersController@store')->name('usersStore');
Route::get('/users/edit/{user}', 'UsersController@edit')->name('usersEdit');
Route::patch('/users/{user}', 'UsersController@update')->name('usersUpdate');
Route::delete('/users/{user}', 'UsersController@destroy')->name('usersDestroy');

/* roles*/
Route::get('/roles', 'RolesController@index')->name('roles');
Route::get('/roles/create', 'RolesController@create')->name('rolesCreate');
Route::get('/roles/{role}', 'RolesController@show')->name('rolesShow');
Route::post('/roles', 'RolesController@store')->name('rolesStore');
Route::get('/roles/edit/{role}', 'RolesController@edit')->name('rolesEdit');
Route::patch('/roles/{role}', 'RolesController@update')->name('rolesUpdate');
Route::delete('/roles/{role}', 'RolesController@destroy')->name('rolesDestroy');

/* test RBAC */
Route::get('/all_links', function () {
    return view ('all_links');
});
