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

Route::get('/', 'PageController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['namespace' => 'Api', 'prefix' => 'api'], function () {
    Route::get('team/{team}/players', 'TeamController@players');
    Route::apiResource('team', 'TeamController');

    Route::apiResource('player', 'PlayerController');
});