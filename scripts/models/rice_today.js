/**
 * Created by kusamao_abe on 15/10/27.
 */

module.exports = Backbone.Model.extend( {

	url:             'http://tambourine.herokuapp.com/v1/rice/today',
	longPolling:     false,
	intervalMinutes: 10,

	/**
	 * ポーリングによるsyncを開始する
	 * @param intervalMinutes Number 単位分でポーリングを行う、デフォルト10
	 */
	startLongPolling: function ( intervalMinutes ) {
		this.longPolling = true;
		if ( intervalMinutes ) this.intervalMinutes = intervalMinutes;
		this._executeLongPolling();
	},
	stopLongPolling: function () {
		this.longPolling = false;
	},
	_executeLongPolling: function () {
		var self = this;
		// thisが書き換わるので必ずthisを束縛する必要がある
		self.fetch( { success: _.bind( self._onFetch, self ) } );
	},
	_onFetch: function () {
		var self = this;
		if ( self.longPolling ) {
			setTimeout( function () {
				self._executeLongPolling();
			}, 1000 * 60 * self.intervalMinutes );
		}
	},

	/**
	 * modelの状態によってどのビューが表示されるべきかを返す関数
	 * @returns {String} 表示されるべきviewの名前
	 */
	getRenderingViewName: function ( id ) {
		var user              = this.get( 'user' ),
			subscriber        = this.get( 'subscriber' ),
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

module.exports.id = 'rice/today';
