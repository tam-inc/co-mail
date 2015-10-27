/**
 * Created by kusamao_abe on 15/10/26.
 */


var template     = require( '../handlebars/test.handlebars' ),
	viewTemplate = template( { test: 'テストだよ' } ),
	$test        = $( '.test' );

$test.html( viewTemplate );

jQuery( function ( $ ) {
	$( 'body' ).append( $test );
} );
