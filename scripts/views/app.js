/**
 * Created by kusamao_abe on 15/10/27.
 */
// Controller的役割を果たす（Backbone.ViewはMVCのVと一対一対応ではない）


module.exports = Backbone.View.extend( {
	el: '.bb-main',

	// initializeはnewされた時に実行されるコンストラクタ
	initialize: function () {

		var UserModel  = require( '../models/rice_me.js' ),
			TodayModel = require( '../models/rice_today.js' );

		var self = this;

		// user modelのcallback定義
		var onSuccess = function ( model, response ) {
			self.model = new TodayModel( { user: response.user } );
			self.model.startLongPolling();
			self.listenTo( self.model, 'change:test', self.changeView );
		};
		var onFailed = function () {
			window.location( '/login' );
		};

		// 自分の認証情報を取得する。失敗した場合は認証画面へ
		this.userModel = new UserModel;
		this.userModel.fetch( {
			success: onSuccess,
			error:   onFailed
		} );

	},

	// TODO: modelが最初にfetchするタイミングでレンダリングビューを決める
	changeView: function () {
		var viewName = this.model.getRenderingViewName();
		console.log( viewName );
	},

	// テスト用
	events: {
		'click .test': 'onClick'
	},

	// テスト用
	onClick: function () {
		console.log( 'click' );
		this.model.set( { test: 'test' } );
	}
} );
