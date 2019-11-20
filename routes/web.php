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
    return view('welcome');
});

Route::group(['middleware' => ['auth']], function () {
    /* Projects */
    Route::resource('projects', 'ProjectsController');

    /*Projects tasks*/
    Route::post('/projects/{project}/tasks', 'ProjectTasksController@store')->name('project.tasks.store');
    Route::patch('/projects/{project}/tasks/{task}', 'ProjectTasksController@update')->name('project.tasks.update');
    Route::delete('/projects/{project}/tasks/{task}', 'ProjectTasksController@destroy')->name('project.tasks.delete');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
