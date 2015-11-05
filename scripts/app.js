/**
 * Created by kusamao_abe on 15/10/26.
 */

( function () {
	// ルーティング開始
	require( './router.js' )();

	// ---------- ユーザがログインしているかチェックする
	// モジュール定義
	var UserModel   = require( './models/rice_me.js' );
	var MainAppView = require( './views/app.js' );

	// ログインしているかどうかのcallback
	var onSuccess = function ( model ) {
		new MainAppView( { model: model } );
	};
	var onFailed = function () {
		window.location( '/login' );
	};

	// 通信開始
	var userModel = new UserModel;
	userModel.fetch( {
		success: onSuccess,
		error:   onFailed
	} );
} )();
