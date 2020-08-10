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

Route::get('balance-entry', 'api\BalanceEntryController@index');
Route::get('balance-entry/total', 'api\BalanceEntryController@total');
Route::get('balance-entry/{id}', 'api\BalanceEntryController@show');
Route::post('balance-entry', 'api\BalanceEntryController@store');
Route::put('balance-entry/{id}', 'api\BalanceEntryController@update');
Route::delete('balance-entry/{id}', 'api\BalanceEntryController@delete');


