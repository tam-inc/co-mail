/**
 * Created by kusamao_abe on 15/10/27.
 */
// Controller的役割を果たす（Backbone.ViewはMVCのVと一対一対応ではない）


module.exports = Backbone.View.extend( {
	el: '.app',
	applyTemplate:   require( '../../handlebars/apply.handlebars' ),
	confirmTemplate: require( '../../handlebars/confirm.handlebars' ),
	resultTemplate:  require( '../../handlebars/result.handlebars' ),

	// initializeはnewされた時に実行されるコンストラクタ
	initialize: function () {
		var self = this;
		var id   = self.model.get( 'id' );

		var TodayRiceModel = require( '../models/rice_today.js' );

		self.listenTo( self.model, 'change:viewName', self.changeViewTemplates );

		var onSuccess = function ( model ) {
			self.todayRiceModel = model;
			var viewName = self.todayRiceModel.getRenderingViewName( id );
			self.model.set( { viewName: viewName } );
		};

		var todayRiceModel = new TodayRiceModel;
		todayRiceModel.fetch( { success: _.bind( onSuccess, self ) } );
	},

	changeViewTemplates: function ( model, viewName ) {
		var self = this;
		Backbone.history.navigate( viewName );
		switch ( viewName ) {
			case 'apply':
				self.template = self.applyTemplate;
				break;
			case 'confirm':
				self.template = self.confirmTemplate;
				break;
			default:
				self.template = self.resultTemplate;
		}
		self.render();
	},

	render: function () {
		var self         = this,
			template     = self.template,
			viewTemplate = template( self.model.toJSON() );
		self.$el.append( viewTemplate );
		return self;
	}
} );
