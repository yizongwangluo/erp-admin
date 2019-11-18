<?php

/**
 * 后台管理员用户
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 9:41
 */
class Admin_user extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'facade/admin_facade' );

		$this->load->model ( 'data/admin_auth_group_data' );
		$this->load->model ( 'data/admin_data' );
	}

	public function lists ()
	{
		$page = max ( 1, $this->input->get ( 'page' ) );
		$result = $this->admin_data->lists_page ( array (), ['id', 'desc'], $page );
		$result['page_html'] = create_page_html ( '?', $result['total'] );
		$this->load->view ( '', $result );
	}

	public function del ( $uid = null )
	{
		if ( $this->input->is_ajax_request () ) {
			if ( !$this->admin_data->delete ( $uid ) ) {
				$this->output->ajax_return ( AJAX_RETURN_FAIL, '删除失败' );
			}
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, '操作成功' );
		}
	}

	public function add ()
	{
		if ( IS_POST ) {
			$user_name = input ( 'user_name' );
			$user_password = input ( 'password' );
			$real_name = input ( 'real_name' );
			$status = input ( 'status' );
			$role_id = input ( 'role_id' );
			$user_id = input ( 'user_id' );
			if (!empty($user_id)){
				if ( !$this->admin_facade->update ($user_id, $user_name, $user_password, $real_name, $role_id, $status ) ) {
					$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->admin_facade->get_error () );
				}
			}else{
				if ( !$this->admin_facade->create ( $user_name, $user_password, $real_name, $role_id, $status ) ) {
					$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->admin_facade->get_error () );
				}
			}
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
		} else {
			$auth_group_list = $this->admin_auth_group_data->lists (array ('status'=>1));
			$this->load->view ( '', ['auth_group_list' => $auth_group_list] );
		}
	}

	public function edit ( $uid = null )
	{
		if ( $uid ) {
			$user['user_info'] = $this->admin_data->get_info ( $uid );
			$user['auth_group_list'] = $this->admin_auth_group_data->lists ();
			$this->load->view ( '@/add', $user );
		} else {
			exit( '该用户不存在' );
		}
	}
}