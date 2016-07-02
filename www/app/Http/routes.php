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

Route::auth();

/* GET Requests */
Route::get('/', 'TextController@example');

Route::get('clients', ['as' => 'clients', 'uses' => 'ClientsController@index']);
Route::get('clients/add', 'ClientsController@add');
Route::get('clients/edit/{id}', 'ClientsController@edit');
Route::get('clients/delete/{id}', 'ClientsController@delete');

/* Post Requests */
Route::post('/', 'TextController@example');
Route::post('clients/create', 'ClientsController@create');
Route::post('clients/update/{id}', 'ClientsController@update');
