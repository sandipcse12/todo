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
Route::get('/tasks', 'TaskController@index');
Route::post('/task', 'TaskController@save');
Route::delete('/tasks', 'TaskController@delete');
Route::put('/complete-task', 'TaskController@completeTask');
Route::put('/task/{id}', 'TaskController@updateTask');
Route::delete('/task/{id}', 'TaskController@deleteTask');

Route::get('/{any}', 'HomeController@index')->where('any', '.*');



