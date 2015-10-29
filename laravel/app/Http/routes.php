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

//ログインしているかチェック
Route::get('/rice/me','AuthController@getUser');

//申し込みを受け付ける
Route::post('/rice/apply', 'RiceController@recept');

//コメールの状態を返す
Route::get('/rice/today', 'RiceController@today');

//google認証
Route::get('/rice/auth','AuthController@auth');

//google認証コールバック
Route::get('/rice/auth/callback','AuthController@googleCallback');


//以下作業用
Route::get('/', function () {
    $session = Session::get('auth');
    var_dump($session);
});

Route::get('/win', 'RiceController@getTodayWinner');

Route::get('/session', function () {
    Session::forget('auth');
});