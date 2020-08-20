<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});

*/

// Authentication Routes...
Route::get('login', [
  'as' => 'login',
  'uses' => 'Auth\LoginController@showLoginForm'
]);
Route::post('login', [
  'as' => '',
  'uses' => 'Auth\LoginController@login'
]);
Route::post('logout', [
  'as' => 'logout',
  'uses' => 'Auth\LoginController@logout'
]);

// Password Reset Routes...
Route::post('password/email', [
  'as' => 'password.email',
  'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset', [
  'as' => 'password.request',
  'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
]);
Route::post('password/reset', [
  'as' => 'password.update',
  'uses' => 'Auth\ResetPasswordController@reset'
]);
Route::get('password/reset/{token}', [
  'as' => 'password.reset',
  'uses' => 'Auth\ResetPasswordController@showResetForm'
]);

// Password Reset Routes...
/*
Route::post('password/email', [
  'as' => 'password.email',
  'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
]);
Route::get('password/reset', [
  'as' => 'password.request',
  'uses' => 'Auth\ResetPasswordController@showResetForm'
]);
Route::post('password/reset', [
  'as' => 'password.update',
  'uses' => 'Auth\ResetPasswordController@reset'
]);
Route::get('password/reset/{token}', [
  'as' => 'password.reset',
  'uses' => 'Auth\ResetPasswordController@showResetForm'
]);
*/
// Registration Routes...
Route::get('register', [
  'as' => 'register',
  'uses' => 'Auth\RegisterController@showRegistrationForm'
]);
Route::post('register', [
  'as' => '',
  'uses' => 'Auth\RegisterController@register'
]);
Route::get('/changePassword','HomeController@showChangePasswordForm');
Route::post('/changePassword','HomeController@changePassword')->name('changePassword');

Route::get('/changeEmail','HomeController@showChangeEmailForm');
Route::post('/changeEmail','HomeController@changeEmail')->name('changeEmail');

Route::get('/', 'HomePage@index');
Route::get('/home', 'HomeController@index');
Route::get('/welcome', 'HomeController@welcome');
Route::post('/saveuserimage', 'HomeController@storeimage')->name('storeimage');


Route::post('/deleteuser/{userid}', 'adminController@deleteuser');
Route::post('/updateuser', 'adminController@updateuser');
Route::post('/searchuser', 'adminController@searchuser');

Route::post('/updateroom', 'adminController@updateroom');
Route::post('/searchuserbyid', 'adminController@searchuserbyid');
Route::post('/addnewroom', 'adminController@addnewroom');
Route::post('/deleteroom', 'adminController@deleteroom');


Route::get('/test', 'HomeController@index');

Route::post('/update_state','HomeController@update_state');
Route::post('/update_dim_state','HomeController@update_dim_state');


Route::post('/savenightmode', 'NightNAutoController@changeNightmode')->name('changeNightmode');
Route::post('/setnightmode','NightNAutoController@nightmode');
Route::post('/setautomode','NightNAutoController@automode');


Route::post('/contactus','HomePage@contactus');
Route::get('/downloadapp', 'downloadController@getmobileapp');