/**
 * Created by kusamao_abe on 15/10/21.
 */

var webpack = require( 'webpack' );
module.exports = {
	entry: {
		'main': './scripts/app.js'
	},
	output: {
		'filename': 'public/js/bundle.js'
	},
	module: {
		'loaders': [
			{ test: /\.html/, loader: 'html' },
			{
				test: /\.handlebars$/,
				loader: "handlebars-loader",
				query: { helperDirs: [ __dirname + '/helpers' ] }
			}
		]
	},
	plugins: [
		new webpack.ProvidePlugin( {
			$:               'jquery',
			jQuery:          'jquery',
			'window.jQuery': 'jquery',
			_:               'underscore',
			Backbone:        'backbone'
		} ),
		new webpack.optimize.UglifyJsPlugin( {
			preservedComments: 'licence',
			mangle: false,
			compress: {
				warnings: false
			}
		} )
	]
};
