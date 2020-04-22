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



Route::get('getStripeData', 'API\ApiController@getStripeData');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('forget-password', 'API\AuthController@resetPassword');

Route::group(['middleware' => ['auth:api', 'roles'], 'namespace' => 'API'], function() {
    
});
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('change-password', 'API\AuthController@changePassword');
    Route::post('update', 'API\AuthController@Update');
    Route::post('get/profile', 'API\AuthController@getProfile');
    Route::post('logout', 'API\AuthController@logout');
    
    Route::post('connectWithStripe', 'API\ApiController@connectWithStripe');
});
Route::post('testing-push', 'API\ConfigurationController@testingPush');
