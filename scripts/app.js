/**
 * Created by kusamao_abe on 15/10/26.
 */

jQuery( function () {
	var UserModel = require( './models/rice/me.js' );
	var Router    = require( './router/router.js' );

	var onSuccess = function ( model ) {
		new Router( { model: model } );
		Backbone.history.start( {
			pushState: true,
			// TODO: tokyo/osakaに切り替える
			root: '/comail/'
		} );
	};
	var onError = function () {
		window.location( '/login' );
	};

	var userModel = new UserModel;
	userModel.fetch( {
		success: onSuccess,
		error:   onError
	} );
} );
