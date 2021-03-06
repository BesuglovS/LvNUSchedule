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

Route::get('/', 'ApiController@index')
    ->name('api.index');

Route::get('/docs', 'ApiController@index')
    ->name('api.index');

Route::post('/import.php', 'ApiController@import')
    ->name('api.import');

Route::get('/api', 'ApiController@api')
    ->name('api.api');

