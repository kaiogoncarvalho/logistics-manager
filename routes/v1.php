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

Route::middleware('guest')->post(
    'oauth/token',
    '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken'
)->name('login');

Route::middleware(['auth:api', 'scope:admin,user'])
    ->group(
        function () {
            Route::prefix('/driver')->group(
                function () {
                    Route::post('', 'DriverController@create');
                    Route::get('/{driver_id}', 'DriverController@getById')
                        ->where(['driver_id' => '[0-9]+']);
                    Route::patch('/{driver_id}', 'DriverController@update')
                        ->where(['driver_id' => '[0-9]+']);
                    Route::delete('/{driver_id}', 'DriverController@delete')
                        ->where(['driver_id' => '[0-9]+']);
                }
            );
            Route::get('/drivers', 'DriverController@getAll');
    
            Route::prefix('/trip')->group(
                function () {
                    Route::post('', 'TripController@create');
                    Route::get('/{trip_id}', 'TripController@getById')
                        ->where(['trip_id' => '[0-9]+']);
                    Route::patch('/{trip_id}', 'TripController@update')
                        ->where(['trip_id' => '[0-9]+']);
                    Route::delete('/{trip_id}', 'TripController@delete')
                        ->where(['trip_id' => '[0-9]+']);
                }
            );
            Route::get('/trips', 'TripController@getAll');
            
            Route::get('/oauth/clients', 'UserController@getClients');
            
            Route::get('/user', 'UserController@get');
        }
    );

Route::middleware(['auth:api', 'scope:admin'])
    ->group(
        function () {
            Route::prefix('/driver')->group(
                function () {
                    Route::get('/deleted/{driver_id}', 'DriverController@getDeletedById')
                        ->where(['driver_id' => '[0-9]+']);
                    Route::patch('/recover/{driver_id}', 'DriverController@recoverById')
                        ->where(['driver_id' => '[0-9]+']);;
                }
            );
            Route::get('drivers/deleted', 'DriverController@getAllDeleted');
    
            Route::prefix('/trip')->group(
                function () {
                    Route::get('/deleted/{trip_id}', 'TripController@getDeletedById')
                        ->where(['trip_id' => '[0-9]+']);
                    Route::patch('/recover/{trip_id}', 'TripController@recoverById')
                        ->where(['trip_id' => '[0-9]+']);;
                }
            );
            Route::get('trips/deleted', 'TripController@getAllDeleted');
            
            Route::prefix('/oauth')->group(
                function () {
                    Route::get('/scopes', '\Laravel\Passport\Http\Controllers\ScopeController@all');
                    Route::delete(
                        '/clients/{client_id}',
                        '\Laravel\Passport\Http\Controllers\ClientController@destroy'
                    )->where(['user_id' => '[0-9]+']);
                }
            );
    
            Route::prefix('/user')->group(
                function () {
                    Route::post('', 'UserController@create');
                    Route::get('/{user_id}', 'UserController@getById')
                        ->where(['user_id' => '[0-9]+']);
                }
            );
            Route::get('/users', 'UserController@getAll');
        }
    );
