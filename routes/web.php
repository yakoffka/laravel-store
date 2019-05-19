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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/readme', function () {
//     return view('readme');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* products*/
Route::get('/products/destroy/{id}', 'ProductsController@destroy');
Route::get('/products/create', 'ProductsController@create');
Route::get('/products/{id}', 'ProductsController@show');
Route::get('/products', 'ProductsController@index')->name('products');
Route::post('/products', 'ProductsController@store');

