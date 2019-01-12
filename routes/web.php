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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


Route::group(['prefix' => '/account', 'middleware'=> ['auth'],  'namespace' => 'Account'], function () {
    Route::get('/', 'AccountController@index')->name('account.index');

    Route::group(['prefix' => '/files'],  function () {
        Route::get('/create', 'FileController@create')->name('account.files.create.start');
        Route::get('/{file}/create', 'FileController@create')->name('account.files.create');
        Route::post('/{file}', 'FileController@store')->name('account.files.store');
        Route::get('/', 'FileController@index')->name('account.files.index');
    });
});