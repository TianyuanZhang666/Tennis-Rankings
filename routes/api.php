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

Route::get('/rank/get', 'TennisRankingsController@get');
Route::get('/rank/delete', 'TennisRankingsController@delete');
Route::get('/rank/update', 'TennisRankingsController@update');
Route::get('/rank/create', 'TennisRankingsController@create');
