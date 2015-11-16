/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Model.extend( {
	url: 'http://tambourine.herokuapp.com/v1/rice/apply',
	validate: function ( data ) {
		if ( data.rice < 0 || data.rice >= 2 ) return 'error';
	}
} );
