/**
 * Created by kusamao_abe on 15/10/27.
 */

module.exports = function () {

	var Router = Backbone.Router.extend( {
		// ルーティング

		routes: {
			'rice/me':       'apply',   // #rice/me
			'rice/me/today': 'confirm', // #rice/me/today
			'rice/today':    'result',  // #rice/today
			'sleep':         'sleep'
		},

		// 申込画面
		apply: function () {
			console.log( 'apply URL' );
		},

		// 確認画面
		confirm: function () {
			console.log( 'confirm URL' );
		},

		// 結果画面
		result: function () {
			console.log( 'result URL' );
		},

		// 睡眠画面
		sleep: function () {
			console.log( 'sleep URL' );
		}
	} );

	new Router();
	Backbone.history.start();
};
