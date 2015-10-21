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

Route::get('/', 'RiseController@auth');

//Route::get('/auth','CallbackController@googleCallback');
Route::get('/auth','CallbackController@googleCallback');

Route::get('/session', function () {

    Session::forget('auth');

});

//ログイン
//アプリの状態を返すもの
//を分ける
