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

Route::post("temprature","apiController@temprature");

Route::post("gettoken","apiController@gettoken");
Route::post("applogin","apiController@applogin");
Route::post("verifytoken","apiController@verifytoken");
Route::post("getroominfo","apiController@getroominfo");
Route::post("setroomstate","apiController@setroomstate");
Route::post("getroomstate","apiController@getroomstate");
Route::post("billprediction","apiController@billprediction");

Route::post("edgesync","apiController@edgesync");
Route::post("logpower","apiController@logpower");
Route::get("HC7sVLHeMMRqFnhZef2hW49w7TJW9sVj","apiController@updatetoken");
Route::get("test","apiController@test");