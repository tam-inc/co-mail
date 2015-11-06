/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Router.extend( {
	todayModel: null,
	routes: {
		'':      'start',
		'apply': 'apply'
	},
	initialize: function ( options ) {
		this.model = options.model;
	},
	apply: function () {
		console.log( 'apply route' );
	},
	start: function () {
		console.log( 'start route' );
		Backbone.history.navigate( '/apply' );
	}
} );
