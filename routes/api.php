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

Route::prefix('auth')->group(function () {
    // маршруты аутентификации
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    // маршруты для информации для авторизованых пользователей
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function () {

    Route::prefix('patients')->namespace('Patients')->group(function () {
        Route::get('/', 'PatientsController@patientsList');

        Route::group(['prefix' => '{patient}', 'where' => ['patient' => '[0-9]+']], function () {
            Route::get('/', 'PatientsController@patientInfo');

            Route::post('attach_diagnos', 'PatientsController@attachDiagnos');
            Route::delete('detach_diagnos', 'PatientsController@detachDiagnos');

            Route::post('attach_reception', 'PatientsController@attachReception');
            Route::post('remove_reception', 'PatientsController@removeReception');

            Route::group(['prefix' => 'diagnos'], function () {
                Route::group(['prefix' => '{diagnos}', 'where' => ['diagnos' => '[0-9]+']], function () {
                    Route::post('attach_product', 'PatientsController@attachProduct');
                    Route::post('detach_product', 'PatientsController@detachProduct');
                });
            });
        });
    });

    Route::prefix('diagnoses')->namespace('Diagnoses')->group(function () {
        Route::get('/', 'DiagnosesController@index');
    });

    Route::prefix('products')->namespace('Products')->group(function () {
        Route::get('/', 'ProductsController@index');
    });


    // Маршруты для чтения и редактирования пользователей в бд
    Route::get('users/{user?}', 'Dashboard\AdminController@usersList')->middleware('isAdmin');
    Route::post('update_user/{user}', 'Dashboard\AdminController@updateUser')->middleware('isAdmin');
    Route::post('delete_user/{user}', 'Dashboard\AdminController@deleteUser')->middleware('isAdmin');
});

Route::post('data', 'DataController@import');
