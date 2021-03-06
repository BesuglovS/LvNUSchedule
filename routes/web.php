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

Route::get('/', "HomeController@index");

Route::get('brb', 'BigRedButton@index')->name('bigredbutton');

Route::get('/api.php', 'OldApiController@api')
    ->name('api.old');

//Route::get('/FlatIndex.php', function () {
//    return redirect('/');
//});

Auth::routes();
