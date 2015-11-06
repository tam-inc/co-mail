/**
 * Created by kusamao_abe on 15/10/21.
 */
var gulp           = require( 'gulp' ),
	plumber        = require( 'gulp-plumber' ),
	webpack        = require( 'webpack-stream' ),
	webpack_config = require( './webpack.config.js' ),
	browserSync    = require( 'browser-sync' ),
	history        = require( 'connect-history-api-fallback' );

gulp.task( 'webpack', function () {
	return gulp.src( [ 'scripts/app.js' ] )
		.pipe( plumber() )
		.pipe( webpack( webpack_config ) )
		.pipe( gulp.dest( './' ) );
} );

gulp.task( 'server', function () {
	browserSync( {
		port: 3200,
		ghostMode: false,
		server: {
			baseDir: './public',
			middleware: [ history( {
				rewrites: [ { from: /\/comail/, to: '/comail/index.html' } ]
			} ) ]
		}
	} )
} );

gulp.task( 'watch', [ 'webpack' ], function () {
	gulp.watch( [ './scripts/**/*.js', './templates/**/*.handlebars' ], [ 'webpack' ] );
} );

gulp.task( 'default', [ 'watch', 'server' ] );
