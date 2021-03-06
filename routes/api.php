<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::post('login', 'Auth\\LoginJwtController@login')->name('login');
    Route::get('logout', 'Auth\\LoginJwtController@logout')->name('logout');
    Route::get('refresh', 'Auth\\LoginJwtController@refresh')->name('refresh');

    Route::group(['middleware' => ['jwt.auth']], function(){

        Route::prefix('produtos')->group(function(){

            Route::get('/', 'ProdutoController@index')->name('index_produtos');
            Route::get('/{id}', 'ProdutoController@show')->name('single_produtos');
            Route::post('/', 'ProdutoController@store')->name('store_produtos');//->middleware('auth.basic');
            Route::put('/{id}', 'ProdutoController@update')->name('update_produtos');
            Route::delete('/{id}', 'ProdutoController@delete')->name('delete_produtos');
        });

        Route::prefix('distribuidores')->group(function(){

            Route::get('/', 'DistribuidorController@index')->name('index_distribuidores');
            Route::get('/{id}', 'DistribuidorController@show')->name('single_distribuidores');
            Route::post('/', 'DistribuidorController@store')->name('store_distribuidores');
            Route::put('/{id}', 'DistribuidorController@update')->name('update_distribuidores');
            Route::delete('/{id}', 'DistribuidorController@delete')->name('delete_distribuidores');

            Route::get('/{id}/produtos', 'DistribuidorController@show_produtos')->name('show_distribuidores_produtos');
            Route::post('/{id}/produtos', 'DistribuidorController@store_produtos')->name('store_distribuidores_produtos');
        });

        Route::name('users.')->group(function(){

            Route::resource('users', 'UserController');
        });

        Route::name('imagens.')->prefix('imagens')->group(function(){
            Route::delete('/{id}', 'ImagemController@remove')->name('delete');

            Route::put('/set-thumb/{photoId}/{realStateId}', 'ImagemController@setThumb')->name('update');
        });
    });

});
