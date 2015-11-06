/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Router.extend( {
	todayModel: null,
	initialize: function ( options ) {
		var self       = this;
		var TodayModel = require( '../models/rice/today.js' );

		self.model      = options.model;
		self.todayModel = new TodayModel;

		self.listenTo( self.todayModel, 'change:viewName', self.changeView );

		var onSuccess = function () {
			var user     = self.model.get( 'user' ),
				id       = user.id,
				viewName = self.todayModel.getRenderingViewName( id );
			self.todayModel.set( { viewName: viewName } );
		};

		self.todayModel.fetch( { success: onSuccess } );
	},

	changeView: function ( model, viewName ) {
		Backbone.history.navigate( '/' + viewName );
	}
} );
