<?php

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

Route::prefix('/oauth')->group(
    function () {
        Route::middleware('guest')->post(
            '/token',
            '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken'
        )->name('login');
        
        Route::middleware(['auth:api', 'scope:admin,user'])->group(
            function () {
                Route::get('/clients', 'UserController@getClients');
            }
        );
        
        Route::middleware(['auth:api', 'scope:admin'])->group(
            function () {
                Route::get('/scopes', '\Laravel\Passport\Http\Controllers\ScopeController@all');
                Route::delete('/clients/{client_id}', '\Laravel\Passport\Http\Controllers\ClientController@destroy');
            }
        );
    }
);

Route::prefix('/user')->group(
    function () {
        Route::middleware(['auth:api', 'scope:admin,user'])->group(
            function () {
                Route::get('', 'UserController@get');
            }
        );
        
        Route::middleware(['auth:api', 'scope:admin'])->group(
            function () {
                Route::post('', 'UserController@create');
                Route::get('/{user_id}', 'UserController@getById');
            }
        );
    }
);

Route::middleware(['auth:api', 'scope:admin'])->get('/users', 'UserController@getAll');

Route::middleware(['auth:api', 'scope:admin,user'])
    ->group(
        function () {
            Route::prefix('driver')->group(function () {
                Route::post('', 'DriverController@create');
                Route::get('/{driver_id}', 'DriverController@getById');
                Route::patch('/{driver_id}', 'DriverController@update');
                Route::delete('/{driver_id}', 'DriverController@delete');
            });
            Route::get('drivers', 'DriverController@get');
        }
    );
