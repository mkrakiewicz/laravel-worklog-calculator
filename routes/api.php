<?php

use App\Models\Role;
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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::group(['middleware' => 'auth:api'], function () {


    Route::group(['middleware' => ['can:access-users']], function () {

        Route::get('users', 'UserAPIController@index');
        Route::post('users', 'UserAPIController@store');

        Route::get('roles', 'RoleAPIController@index');
        Route::post('roles', 'RoleAPIController@store');

        Route::get('role_users', 'RoleUsersAPIController@index');
        Route::post('role_users', 'RoleUsersAPIController@store');


        Route::group(['middleware' => ['can:modify-users,user']], function () {

            Route::resource('users', 'UserAPIController', ['except' => ['index','store']]);
            Route::resource('roles', 'RoleAPIController', ['except' => ['index','store']]);
            Route::resource('role_users', 'RoleUsersAPIController', ['except' => ['index','store']]);


        });

    });

    Route::group(['middleware' => ['can:access-worklogs']], function () {

        Route::get('worklogs', 'worklogAPIController@index');
        Route::post('worklogs', 'worklogAPIController@store');

        Route::group(['middleware' => ['can:modify-worklogs,worklog']], function () {

            Route::resource('worklogs', 'worklogAPIController', ['except' => ['index','store']]);
        });
    });

    Route::get('users_worklogs_settings/usersOverview',
        'UsersWorklogsSettingsAPIController@usersOverview')->middleware(['can:access-user-settings']);

    Route::group(['middleware' => ['can:access-user-settings']], function () {

        Route::get('users_worklogs_settings', 'UsersWorklogsSettingsAPIController@index');
        Route::get('users_worklogs_settings', 'UsersWorklogsSettingsAPIController@store');


        Route::group(['middleware' => ['can:modify-user-settings']], function () {

            Route::resource('users_worklogs_settings', 'UsersWorklogsSettingsAPIController', ['except' => ['index']]);

        });
    });

});

Route::post('/register', 'RegistrationAPIController@store');
