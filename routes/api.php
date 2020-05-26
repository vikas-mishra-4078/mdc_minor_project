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

Route::post('send-otp', 'API\UserController@sendOtp');
Route::post('resend-otp', 'API\UserController@resendOtp');
Route::post('verify-otp', 'API\UserController@verifyOtp');
Route::post('login', 'API\UserController@login');

Route::post('pages', 'API\HomepageController@pages');
