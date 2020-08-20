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


Route::get("HC7sVLHeMMRqFnhZef2hW49w7TJW9sVj","apiController@updatetoken");
Route::get("test","apiController@test");


Route::get("mSjei38N2MEi3kM246Hkaf87Ao4odj8OQmsfjGaDO3eEi84Mh374gYGDh","cronJobController@automodedayjob");
Route::get("D8N2MEi3kM246H4gYG7Ao4odj8OQmsfjGaDO3eEhkaf8mSjei3i84Mh37","cronJobController@automodenightjob");
Route::get("GaM2a48MjHhQmsfaD4g7Ao4i76HmSjs3kM2jO3S3YGDh46Ei3kf8fk4DG","cronJobController@automodedaysetstate");
Route::get("OQm2MYG7Ao4hkaf3O3eEN2MEi3dj8oodj8O7gmi38NeD8i84MhjeeEi84","cronJobController@automodenightsetstate");