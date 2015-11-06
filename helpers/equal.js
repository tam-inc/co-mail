/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = function ( value, comparison, block ) {
	if ( value === comparison ) return block.fn( this );
	return block.inverse( this );
};
