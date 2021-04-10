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
        Route::post('add', 'PatientsController@patientAdd');
        Route::group(['prefix' => '{patient}', 'where' => ['patient' => '[0-9]+']], function () {
            Route::get('/', 'PatientsController@patientInfo');
            Route::put('/', 'PatientsController@update');

            Route::post('attach_diagnos', 'PatientsController@attachDiagnos');
            Route::delete('detach_diagnos', 'PatientsController@detachDiagnos');

            Route::post('attach_reception', 'PatientsController@attachReception');
            Route::post('remove_reception', 'PatientsController@removeReception');

            Route::post('get_module_status', 'PatientsController@getModuleDownloadStatus');

            Route::group(['prefix' => 'diagnos'], function () {
                Route::group(['prefix' => '{diagnos}', 'where' => ['diagnos' => '[0-9]+']], function () {
                    Route::post('attach_product', 'PatientsController@attachProduct');
                    Route::post('detach_product', 'PatientsController@detachProduct');

                    Route::post('update', 'PatientsController@updateRecord');
                });
            });
        });
    });

    Route::prefix('products')->namespace('Products')->group(function () {
        Route::get('/', 'ProductsController@index');
        Route::post('create_product', 'ProductsController@createProduct');
        Route::group(['prefix' => '{product}', 'where' => ['product' => '[0-9]+']], function () {
            Route::put('update_product', 'ProductsController@updateProduct');
            Route::delete('remove_product', 'ProductsController@removeProduct');
            Route::get('/', 'ProductsController@getProductInfo');
        });
    });

    Route::prefix('modules')->namespace('Products')->group(function () {
        Route::get('/', 'ModulesController@getModules');
        Route::post('create_module', 'ModulesController@createModule');
        Route::group(['prefix' => '{module}', 'where' => ['module' => '[0-9]+']], function () {
            Route::put('update_module', 'ModulesController@updateModule');
            Route::delete('remove_module', 'ModulesController@removeModule');
            Route::get('/', 'ModulesController@getModuleInfo');
        });
    });

    Route::prefix('diagnoses')->namespace('Diagnoses')->group(function () {
        Route::get('/', 'DiagnosesController@index');
        Route::post('create', 'DiagnosesController@create');
        Route::group(['prefix' => '{diagnos}', 'where' => ['diagnos' => '[0-9]+']], function () {
            Route::put('update', 'DiagnosesController@update');
            Route::delete('remove', 'DiagnosesController@remove');
            Route::get('/', 'DiagnosesController@getDiagnosInfo');
        });
    });

    // Маршруты дляработы с пользователями в бд
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'Dashboard\AdminController@usersList')->middleware('isAdmin');

        Route::group(['prefix' => '{user}', 'where' => ['user' => '[0-9]+']], function () {
            Route::get('/', 'Dashboard\AdminController@getUser')->middleware('isAdmin');
            Route::put('update_user', 'Dashboard\AdminController@updateUser')->middleware('isAdmin');
            Route::delete('delete_user', 'Dashboard\AdminController@deleteUser')->middleware('isAdmin');
        });
    });

    Route::get('logs', 'LogsController@index');

    Route::post('get_data', 'DataController@getData');
    Route::post('get_periods', 'DataController@getPeriods');
    Route::post('run_assessment', 'DataController@runAssessment');
});

Route::post('data', 'DataController@import');
