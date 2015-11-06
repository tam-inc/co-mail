/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.View.extend( {
	el: '.confirm-form',
	template: require( '../../../templates/confirm/form.handlebars' ),
	initialize: function () {
		console.log( this.model.attributes );
	},
	render: function () {
		var self         = this,
			template     = self.template,
			viewTemplate = template( self.model.toJSON() );
		self.$el.append( viewTemplate );
		return self;
	}
} );
