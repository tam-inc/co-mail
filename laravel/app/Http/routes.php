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

Route::get('/', 'RiceController@getUser');

Route::get('/auth','AuthController@auth');
Route::get('/auth/callback','AuthController@googleCallback');

Route::post('/apply', 'RiceController@apply');

Route::get('/session', function () {
    Session::forget('auth');
});

Route::get('/form', function () {
    return view('form');
});