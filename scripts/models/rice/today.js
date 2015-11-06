/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Model.extend( {
	url: 'http://tambourine.herokuapp.com/v1/rice/today',
	/**
	 * modelの状態によってどのビューが表示されるべきかを返す関数
	 * @returns {String} 表示されるべきviewの名前
	 */
	getRenderingViewName: function ( id ) {
		var subscriber        = this.get( 'subscriber' ),
			is_in_apply_time  = this.get( 'is_in_apply_time' ),
			is_in_result_time = this.get( 'is_in_result_time' );

		if ( !is_in_apply_time && !is_in_result_time ) return 'sleep';
		if ( is_in_result_time ) return 'result';
		if ( is_in_apply_time ) {
			var isDoneApply =  Boolean( _.findWhere( subscriber, { id: id } ) );
			return ( isDoneApply ) ? 'confirm' : 'apply';
		}
		return 'apply';
	}
} );
