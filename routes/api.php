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
    Route::get('logout', 'API\AuthController@logout');
    Route::post('tournament/store', 'API\TournamentsController@createTournaments');
    Route::post('tournament/list', 'API\TournamentsController@tournamentList');
    Route::post('tournament/score', 'API\TournamentsController@addScoreToTournament');
    Route::post('tournament', 'API\TournamentsController@getTournament');
    
    Route::post('users', 'API\TournamentsController@findFriend');
    
    Route::post('friends/store', 'API\TournamentsController@addFriend');
    Route::post('friends', 'API\TournamentsController@myFriends');
    Route::post('friends/requests', 'API\TournamentsController@pendingRequests');
    Route::post('friends/accept', 'API\TournamentsController@acceptRejectRequests');
    
    Route::post('chat/store', 'API\MessageController@store');
    Route::post('chat/getItems', 'API\MessageController@getItems');
    Route::post('chat/getItemsByReceiverId', 'API\MessageController@getItemsByReceiverId');
    
    
    Route::post('connectWithStripe', 'API\ApiController@connectWithStripe');
    
    Route::post('game/twitch', 'API\TournamentsController@getVideosByTwitchId');
    Route::post('game/teams', 'API\TournamentsController@teamList');
    Route::post('game/clubs', 'API\TournamentsController@clubList');
    Route::post('game/players', 'API\TournamentsController@playerList');
    
    Route::post('notification/list', 'API\TournamentsController@notifications');
    Route::post('notification/count', 'API\TournamentsController@notificationCount');
});
Route::post('testing-push', 'API\ConfigurationController@testingPush');
    
