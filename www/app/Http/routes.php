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

Route::get('/', 'TextController@example');
Route::post('/', 'TextController@example');

Route::get('/home', 'TextController@example');
Route::post('/home', 'TextController@example');

Route::get('/text/example', 'TextController@example');
Route::post('/text/example', 'TextController@example');
