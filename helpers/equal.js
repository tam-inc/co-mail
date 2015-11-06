/**
 * Created by kusamao_abe on 2015/11/06.
 */
var _ = require( 'underscore' );

module.exports = function ( value, comparison, block ) {
	if ( _.isNumber( value ) ) value = String( value );
	if ( value === comparison ) return block.fn( this );
	return block.inverse( this );
};
