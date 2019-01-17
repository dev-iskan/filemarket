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

Route::get('/test', function ()  {
    dd(auth()->user()->hasRole('admin '));
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


Route::group(['prefix' => '/account', 'middleware'=> ['auth'],  'namespace' => 'Account'], function () {
    Route::get('/', 'AccountController@index')->name('account.index');

    Route::group(['prefix' => '/files'],  function () {
        Route::get('/create', 'FileController@create')->name('account.files.create.start');
        Route::get('/{file}/create', 'FileController@create')->name('account.files.create');
        Route::post('/{file}', 'FileController@store')->name('account.files.store');
        Route::get('/', 'FileController@index')->name('account.files.index');
        Route::get('/{file}/edit', 'FileController@edit')->name('account.files.edit');
        Route::patch('/{file}', 'FileController@update')->name('account.files.update');
    });
});

Route::group(['prefix' => '/admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'AdminController@index')->name('admin.index');
    Route::get('/{file}', 'FileController@show')->name('admin.files.show');
    Route::group(['prefix'=> '/files'], function () {
        Route::group(['prefix'=> '/new'], function () {
            Route::get('/', 'FileNewController@index')->name('admin.files.new.index');
            Route::patch('/{file}', 'FileNewController@update')->name('admin.files.new.update');
            Route::delete('/{file}', 'FileNewController@destroy')->name('admin.files.new.destroy');
        });
        Route::group(['prefix'=> '/updated'], function () {
            Route::get('/', 'FileUpdatedController@index')->name('admin.files.updated.index');
            Route::patch('/{file}', 'FileUpdatedController@update')->name('admin.files.updated.update');
            Route::delete('/{file}', 'FileUpdatedController@destroy')->name('admin.files.updated.destroy');
        });
    });
});

Route::group(['prefix' => '/{file}/checkout', 'namespace' => 'Checkout'], function () {
    Route::post('/free', 'CheckoutController@free')->name('checkout.free');
});

Route::post('/{file}/upload', 'Upload\UploadController@store')->name('upload.store');
Route::delete('/{file}/upload/{upload}', 'Upload\UploadController@destroy')->name('upload.destroy');

Route::get('/{file}', 'Files\FileController@show')->name('files.show');