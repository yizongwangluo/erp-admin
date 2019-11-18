<?php

/**
 * 后台菜单调整
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/26 0026
 * Time: 9:33
 */
class Menu extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'facade/menu_facade' );
		$this->load->model ( 'data/menu_data' );
	}

	private function getMenu ( Closure $call, $add = '' )
	{
		if ( $add == 'lists' ) {
			$menuList = $this->menu_facade->getMenuList ( '', 'asc' );
		} else {
			$menuList = $this->menu_facade->getMenuList ( array ('status' => 1), 'asc' );
		}
		$newData['list'] = array2level ( $menuList );
		$call( $newData );
	}

	public function lists ()
	{
		$this->getMenu ( function ( $newData ) {
			$this->load->view ( '', $newData );
		}, 'lists' );
	}

	public function add ()
	{
		$this->getMenu ( function ( $newData ) {
			$this->load->view ( '@/edit', $newData );
		} );
	}

	public function edit ( $id )
	{
		$this->getMenu ( function ( $menulist ) use ( &$id ) {
			$menulist['info'] = $this->menu_data->get_info ( $id );
			$this->load->view ( '@/edit', $menulist );
		} );
	}

	public function del ()
	{
		$dat = (int)$this->input->post ( 'id' );
		$result = $this->menu_facade->delMenu ( $dat );
		if ( !$result ) {
			$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->menu_facade->get_error () );
			return false;
		} else {
			$this->delmenuCahce ();
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
		}
	}

	/**
	 * 删除缓存
	 */
	private function delmenuCahce ()
	{
		$this->cache->clean();
	}

	public function save ()
	{
		$dat = $this->input->post ();
		if ( !empty( $dat['id'] ) ) {
			$result = $this->menu_facade->updateMenu ( $dat );
		} else {
			$result = $this->menu_facade->addMenu ( $dat );
		}
		if ( !$result ) {
			$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->menu_facade->get_error () );
			return false;
		} else {
			$this->delmenuCahce ();
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
		}

	}
}