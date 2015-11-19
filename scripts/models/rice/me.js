/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Model.extend( {
	// TODO: /api/{area}/rice/meの方式にします
	url: 'http://tambourine.herokuapp.com/v1/rice/me'
} );
