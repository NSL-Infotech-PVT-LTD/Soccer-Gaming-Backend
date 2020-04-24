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


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => ['Super-Admin']], function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('users/role/{role_id}', 'Admin\UsersController@indexByRoleId')->name('users-role');
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

    Route::resource('configuration', 'Admin\ConfigurationController');
    Route::get('configuration', 'Admin\ConfigurationController@customEdit');


    Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
    Route::resource('vehicle-damage', 'Admin\VehicleDamageController');
});

//Route::resource('admin/products', 'Admin\\ProductsController');
//Route::resource('admin/product-category', 'Admin\\ProductCategoryController');
//Route::resource('admin/service-provider-availability', 'Admin\\ServiceProviderAvailabilityController');
//Route::resource('admin/vehicle-damage', 'Admin\\VehicleDamageController');
//Route::resource('admin/user-vehicle', 'Admin\\UserVehicleController');
//Route::resource('admin/user-job', 'Admin\\UserJobController');
//Route::resource('admin/user-job-proposal', 'Admin\\UserJobProposalController');
//Route::resource('admin/notification', 'Admin\\NotificationController');
//Route::resource('admin/job-issues', 'Admin\\JobIssuesController');
//Route::resource('admin/job-issues', 'Admin\\JobIssuesController');
//Route::resource('admin/job-issue-images', 'Admin\\JobIssueImagesController');
//Route::resource('admin/challenges', 'Admin\\challengesController');
Route::resource('admin/metas', 'Admin\MetasController');
Route::get('admin/privacy_policy', 'Admin\MetasController@privacy_policy');
Route::get('admin/terms_and_condition', 'Admin\MetasController@terms_and_condition');

Route::resource('admin/messag-messages', 'Admin\\MessagMessagesController');

Route::resource('admin/conversations', 'Admin\\ConversationsController');
Route::resource('admin/chats', 'Admin\\ChatsController');

Route::resource('admin/tournament', 'Admin\\TournamentController');