/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.View.extend( {
	el: '.confirm-form',
	template: require( '../../../templates/confirm/form.handlebars' ),
	events: {
		'change .rice_quantity': 'onChange',
		'click button':          'onSubmit'
	},
	initialize: function () {
		this.listenTo( this.model, 'invalid', this.onError );
		this.rice = this.model.get( 'rice' );
	},
	render: function () {
		var self         = this,
			template     = self.template,
			viewTemplate = template( self.model.toJSON() );
		self.$el.append( viewTemplate );
		return self;
	},
	onSubmit: function () {
		this.model.set( { rice: this.rice }, { validate: true } );
	},
	onChange: function () {
		this.rice = Number( this.$( '.rice_quantity' ).val() );
	},
	onError: function ( args ) {
		console.error( args );
	}
} );
