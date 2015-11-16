/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.View.extend( {

	el: '.app',
	template: require( '../../templates/apply.handlebars' ),

	className: 'apply',
	initialize: function () {
		console.log( this );
	}
} );
