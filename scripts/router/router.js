/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Router.extend( {
	todayModel: null,
	initialize: function ( options ) {
		var self       = this;
		var TodayModel = require( '../models/rice/today.js' );

		self.model      = options.model;
		self.todayModel = new TodayModel( { user: self.model.get( 'user' ) } );

		self.listenTo( self.todayModel, 'change:viewName', self.changeView );

		var onSuccess = function () {
			var viewName = self.todayModel.getRenderingViewName();
			self.todayModel.set( { viewName: viewName } );
		};

		self.todayModel.fetch( { success: onSuccess } );
	},

	changeView: function ( model, viewName ) {
		Backbone.history.navigate( '/' + viewName );
	}
} );
