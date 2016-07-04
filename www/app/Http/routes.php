<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/* GET Requests */
Route::get('/', 'TextController@example');

Route::get('login', 'Auth\AuthController@showLoginForm');
Route::get('logout', 'Auth\AuthController@logout');

Route::get('clients', ['as' => 'clients', 'uses' => 'ClientsController@index']);
Route::get('clients/add', 'ClientsController@add');
Route::get('clients/edit/{id}', 'ClientsController@edit');
Route::get('clients/delete/{id}', 'ClientsController@delete');

Route::get('users', ['as' => 'users', 'uses' => 'UsersController@index']);
Route::get('users/create', 'UsersController@create');
Route::get('users/delete/{id}', 'UsersController@delete');

/* Post Requests */
Route::post('/', 'TextController@example');

Route::post('login', 'Auth\AuthController@login');

Route::post('clients/create', 'ClientsController@create');
Route::post('clients/update/{id}', 'ClientsController@update');

Route::post('users/store', 'UsersController@store');
