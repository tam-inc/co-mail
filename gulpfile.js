/**
 * Created by kusamao_abe on 15/10/21.
 */
var gulp           = require( 'gulp' ),
	plumber        = require( 'gulp-plumber' ),
	webpack        = require( 'webpack-stream' ),
	webpack_config = require( './webpack.config.js' ),
	browserSync    = require( 'browser-sync' ),
	history        = require( 'connect-history-api-fallback' ),
	shell          = require( 'gulp-shell' );

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
				// TODO: tokyo, osakaで変更します
				rewrites: [ { from: /\/comail/, to: '/comail/index.html' } ]
			} ) ]
		}
	} )
} );

gulp.task( 'php', function () {
	shell( 'php -S localhost:8000 -t public' )
} );

gulp.task( 'watch', [ 'webpack' ], function () {
	gulp.watch( [ './scripts/**/*.js', './templates/**/*.handlebars' ], [ 'webpack' ] );
} );

gulp.task( 'default', [ 'watch', 'php', 'server' ] );

// TODO: gulpでjshint一応走らせる・mochaのテストをタスク化する
