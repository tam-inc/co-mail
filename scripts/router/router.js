/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Router.extend( {
	todayModel: null,

	ApplyView:   require( '../views/apply.js' ),
	ConfirmView: require( '../views/confirm.js' ),

	initialize: function ( options ) {
		var self       = this;
		var TodayModel = require( '../models/rice/today.js' );

		self.model      = options.model;
		self.todayModel = new TodayModel( { user: self.model.get( 'user' ) } );

		self.listenTo( self.todayModel, 'change:viewName', self.changeView );

		self.todayModel.fetch();
	},

	changeView: function ( model, viewName ) {
		var self = this;
		Backbone.history.navigate( '/' + viewName );
		var viewNameKey = viewName.charAt(0).toUpperCase() + viewName.slice(1) + 'View',
			View = this[ viewNameKey ],
			view = new View( { model: self.todayModel } );
		view.render();
	}
} );
