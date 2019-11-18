<?php
/**
 * 公共Api接口
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/7 0007
 * Time: 11:11
 */
class Api extends \Application\Component\Common\AdminPermissionValidateController{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/game_supplier_data' );
		$this->load->model ( 'data/store_data' );
		$this->load->model ( 'facade/game_facade' );

	}

	/**
	 * 获取游戏
	 * @param type 传入游戏类型
	 */
	public function get_games ()
	{
		if ( $this->input->is_ajax_request () ) {
			$type = input ( 'type', '0' );
			$keyword = input ( 'keyword' );
			$all_game = $this->game_facade->getGameList ( $type, $keyword );
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok', $all_game );
		}
	}

	/**
	 * 获取平台
	 */
	public function get_supplier ()
	{
		$where = array ();
		if ( !empty( $keyword = trim ( input ( 'q' ) ) ) ) {
			$condition[] = "name like '%{$keyword}%'";
			$where = implode ( ' and ', $condition );
		}
		$result = $this->game_supplier_data->lists ( $where, ['id', 'desc'], 18 );
		if ( $this->input->is_ajax_request () ) {
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok', $result );
		} else {
			return $result;
		}
	}

	/**
	 * 获取店铺
	 */
	public function get_store ()
	{
		$condition[] = " store_status=1 ";
		if ( !empty( $keyword = trim ( input ( 'q' ) ) ) ) {
			$condition[] = "store_name like '%{$keyword}%'";
		}
		$where = implode ( ' and ', $condition );
		$result = $this->store_data->lists ( $where );
		if ( $this->input->is_ajax_request () ) {
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok', $result );
		} else {
			return $result;
		}
	}
}