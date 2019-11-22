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

Route::namespace('API')->name('api.')->group(function(){
	Route::prefix('produtos')->group(function(){

		Route::get('/', 'ProdutoController@index')->name('index_produtos');
		Route::get('/{id}', 'ProdutoController@show')->name('single_produto');

		Route::post('/', 'ProdutoController@store')->name('store_produtos');
		Route::put('/{id}', 'ProdutoController@update')->name('update_produto');

		Route::delete('/{id}', 'ProdutoController@delete')->name('delete_produto');
	});
});
