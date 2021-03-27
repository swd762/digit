<?php

use Illuminate\Support\Facades\Route;

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

// Route * to vue router template
// Route::get('/', function () {
//     return view('index');
// });

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');

// Route to handle page reload, except api routes
// Route::get('/{any?}/{any2?}', function (){
//     return view('index');
// })->where('any', '^(?!api\/)[\/\w\.-]*')->where('any2', '^(?!api\/)[\/\w\.-]*');
