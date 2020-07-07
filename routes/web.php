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

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('forget/success', 'HomeController@forgetsuccess')->name('forget.success');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => ['Super-Admin']], function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('users/role/{role_id}', 'Admin\UsersController@indexByRoleId')->name('users-role');
    Route::get('tournamentPlayers/{tournament_id}', 'Admin\TournamentController@showTournamentPlayers');
    Route::get('playerFixtures/{player_id}', 'Admin\TournamentController@showTournamentPlayerFixtures');
    Route::get('editfixture/{fixture_id}', 'Admin\TournamentController@editTournamentFixture');
    Route::any('updateTournamentFixture/{fixture_id}', 'Admin\TournamentController@updateTournamentFixture');
    Route::get('playerfriends/{user_id}', 'Admin\UsersController@showPlayerFriends');
    Route::get('/', 'Admin\AdminController@index');
    Route::resource('roles', 'Admin\RolesController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::resource('users', 'Admin\UsersController');
    Route::get('provider', 'Admin\UsersController@provider');
    Route::get('TotalActiveJobs', 'Admin\UsersController@TotalActiveJobs');
    Route::get('posts', 'Admin\UsersController@notGetOffer');
    Route::get('user-status', 'Admin\UsersController@updateUserStatus');
    Route::resource('pages', 'Admin\PagesController');
    Route::resource('activitylogs', 'Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    Route::resource('settings', 'Admin\SettingsController');
    Route::post('user/change-status', 'Admin\UsersController@changeStatus')->name('user.changeStatus');
    Route::post('tournament/change-status', 'Admin\TournamentController@changeStatus')->name('tournament.changeStatus');
    Route::post('banner/change-status', 'Admin\BannerController@changeStatus')->name('banner.changeStatus');
    Route::resource('teams', 'Admin\\TeamsController');
    Route::resource('banner', 'Admin\\BannerController');
    Route::resource('configuration', 'Admin\ConfigurationController');
    Route::get('configuration', 'Admin\ConfigurationController@customEdit');
    Route::resource('tournament', 'Admin\\TournamentController');

    Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
});
