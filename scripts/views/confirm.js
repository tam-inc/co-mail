/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.View.extend( {
	el: '.app',
	template:   require( '../../templates/confirm.handlebars' ),
	FormView:   require( './confirm/form.js' ),
	ApplyModel: require( '../models/rice/apply.js' ),
	initialize: function () {
	},
	render: function () {
		var self         = this,
			template     = self.template,
			viewTemplate = template( self.model.toJSON() );
		self.$el.append( viewTemplate );
		self.assign();
		return self;
	},
	assign: function () {
		var user         = this.model.get( 'user' ),
			subscriber   = this.model.get( 'subscriber' ),
			userRiceData = _.findWhere( subscriber, { id: user.id } );
		this.applyModel = new this.ApplyModel( userRiceData );
		this.formView   = new this.FormView( { model: this.applyModel } );
		this.formView.render();
	}
} );
