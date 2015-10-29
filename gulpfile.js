/**
 * Created by kusamao_abe on 15/10/21.
 */
var gulp           = require( 'gulp' ),
	plumber        = require( 'gulp-plumber' ),
	webpack        = require( 'webpack-stream' ),
	webpack_config = require( './webpack.config.js' ),
	browserSync    = require( 'browser-sync' );

gulp.task( 'webpack', function () {
	return gulp.src( [ 'scripts/app.js' ] )
		.pipe( plumber() )
		.pipe( webpack( webpack_config ) )
		.pipe( gulp.dest( './' ) );
} );

gulp.task( 'server', function () {
	browserSync( {
		port: 3200,
		server: {
			baseDir: './public'
		}
	} );
} );

gulp.task( 'watch', [ 'webpack' ], function () {
	gulp.watch( [ './scripts/**/*.js' ], [ 'webpack' ] );
} );

gulp.task( 'default', [ 'watch', 'server' ] );
