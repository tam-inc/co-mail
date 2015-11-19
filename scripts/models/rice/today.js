/**
 * Created by kusamao_abe on 2015/11/06.
 */

module.exports = Backbone.Model.extend( {
	// TODO: /api/{area}/rice/todayの方式にします
	url: 'http://tambourine.herokuapp.com/v1/rice/today',
	// このModelがInstance化されている時、通信成功するとviewNameをsetする
	initialize: function () {
		var self = this;
		self.listenTo( self, 'sync', self.setViewName );
	},
	/**
	 * modelの状態によってどのビューが表示されるべきかを返す関数
	 * @returns {String} 表示されるべきviewの名前
	 */
	getRenderingViewName: function () {
		// userはnewされる時に渡されている
		var user              = this.get( 'user' ),
			subscriber        = this.get( 'subscriber' ),
			is_in_apply_time  = this.get( 'is_in_apply_time' ),
			is_in_result_time = this.get( 'is_in_result_time' );

		if ( !is_in_apply_time && !is_in_result_time ) return 'sleep';
		if ( is_in_result_time ) return 'result';
		if ( is_in_apply_time ) {
			// 自身のユーザIDが申し込み一覧にあるかどうか調べる、あればisDoneApplyはtrue
			var isDoneApply =  Boolean( _.findWhere( subscriber, { id: user.id } ) );
			return ( isDoneApply ) ? 'confirm' : 'apply';
		}
		return 'apply';
	},
	setViewName: function () {
		var viewName = this.getRenderingViewName();
		this.set( { viewName: viewName } );
	}
} );
