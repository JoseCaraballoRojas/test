<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* 
|------------------------------------------------------------------------
| Routes for the management of tastes
|------------------------------------------------------------------------
|
*/
Route::get('/products', 'ProductController@index')->name('products.all');
Route::post('/products', 'ProductController@store')->name('products.store');
Route::get('/products/{product}', 'ProductController@show')->name('products.show');
Route::put('/products/{product}', 'ProductController@update')->name('products.update');
Route::delete('/products/{product}', 'ProductController@destroy')->name('products.destroy');
/* 
|------------------------------------------------------------------------
| Routes to manage the assembly of the boxes
|------------------------------------------------------------------------
|
*/
Route::group(['prefix' => 'v1', 'middleware' => 'cors'], function () {
    /** Products Routes */
    Route::get('/products', 'ProductController@index')->name('products.all');
    /** Boxes Routes */
    Route::get('/boxes/top', 'BoxController@top')->name('boxes.top');
    Route::post('/boxes', 'BoxController@store')->name('boxes.store');
});