<?php

/**
 * 用户授权组
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/19 0019
 * Time: 14:22
 */
class Auth_group extends \Application\Component\Common\AdminPermissionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/admin_auth_group_data' );
		$this->load->model ( 'data/menu_data' );
		$this->load->model ( 'facade/admin_auth_group_facade' );
	}

	public function lists ()
	{
		$auth_data['data'] = $this->admin_auth_group_data->lists ();
		$this->load->view ( '', $auth_data );
	}

	public function add ()
	{
		if ( IS_POST ) {
			$input = input ( 'post.' );
			if ( !$this->admin_auth_group_facade->add ( $input ) ) {
				$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->admin_auth_group_facade->get_error () );
			}
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
		} else {
			$this->load->view ( '' );
		}
	}

	public function del ( $id = null )
	{
		empty( $id ) && $this->output->error ( '参数不能为空哦！' );
		if ( $id == 1 ) {
			$this->output->ajax_return ( AJAX_RETURN_FAIL, '超级管理员不能删除哦！' );
		}
		if ( !$this->admin_auth_group_data->delete ( $id ) ) {
			$this->output->ajax_return ( AJAX_RETURN_FAIL, '数据删除更新失败！' );
		}
		$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
	}

	public function edit ( $id = null )
	{
		if ( $id == 1 ) {
			$this->output->ajax_return ( AJAX_RETURN_FAIL, '超级管理员不能编辑哦！' );
		}
		if ( $this->input->is_ajax_request () ) {
			$data['info'] = $this->admin_auth_group_data->get_info ( $id );
			$this->load->view ( '@/add', $data );
		}
	}

	/**
	 * 节点授权
	 */
	public function auth_node ( $id = null )
	{
		$this->load->view ( '', ['id' => $id] );
	}

	public function getjson ()
	{
		$id  = input ('id');
		$auth_group_data = $this->admin_auth_group_data->get_info ( $id );
		$auth_rules = explode ( ',', $auth_group_data['rules'] );

		$auth_rule_list = $this->menu_data->get_field_by_where ( 'id,pid,name', '' ,true);
		foreach ( $auth_rule_list as $key => $value ) {
			in_array ( $value['id'], $auth_rules ) && $auth_rule_list[$key]['checked'] = true;
		}

		echo_json ($auth_rule_list);
	}

	/**
	 * 更新权限组规则
	 * @param $id
	 * @param $auth_rule_ids
	 */
	public function updateAuthGroupRule()
	{
		if (IS_POST) {
			$id = input ('id');
			$auth_rule_ids = input ('auth_rule_ids');
			if ($id) {
				$group_data['rules'] = is_array($auth_rule_ids) ? implode(',', $auth_rule_ids) : '';
				if ($this->admin_auth_group_data->update($id,$group_data) !== false) {
					$this->output->ajax_return (AJAX_RETURN_SUCCESS,'操作成功');
				} else {
					$this->output->ajax_return (AJAX_RETURN_FAIL,'操作失败哦');
				}
			}
		}
	}
}