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

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');

Route::get('/categories', 'CategoriesController@index');
Route::get('/categories/{id}/products', 'CategoriesController@products');

Route::get('/product/{id}', 'ProductsController@view');

Route::group(['middleware' => 'auth.jwt'], function(){
    Route::post('/product', 'ProductsController@create');
    Route::put('/product/{id}', 'ProductsController@update');
    Route::delete('/product/{id}', 'ProductsController@delete');
});
