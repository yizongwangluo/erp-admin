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
		$this->load->model ( 'data/admin_organization_data' );
	}

	public function lists ()
	{
		$id = $this->input->get('id');
		$where = [];
		if($id){ $where['id'] = $id; }
		$page = max ( 1, $this->input->get ( 'page' ) );
		$result = $this->admin_data->lists_page ( $where, ['id', 'desc'], $page );
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
			$org_id = input ( 'org_id' );
			if (!empty($user_id)){
				if ( !$this->admin_facade->update ($user_id, $user_name, $user_password, $real_name, $role_id, $status,$org_id ) ) {
					$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->admin_facade->get_error () );
				}
			}else{
				if ( !$this->admin_facade->create ( $user_name, $user_password, $real_name, $role_id, $status,$org_id ) ) {
					$this->output->ajax_return ( AJAX_RETURN_FAIL, $this->admin_facade->get_error () );
				}
			}
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );
		} else {

			$auth_group_list = $this->admin_auth_group_data->lists (array ('status'=>1));
			foreach($auth_group_list as $k=>$v){
				$auth_group_list[$k]['name'] = $v['title'];
				unset($auth_group_list[$k]['title']);
			}

			$org_list = $this->org_list();
			$this->load->view ( '', ['auth_group_list' => $auth_group_list,'org_list'=>$org_list] );
		}
	}

	public function edit ( $uid = null )
	{
		if ( $uid ) {
			$user['user_info'] = $this->admin_data->get_info ( $uid );
			$user['auth_group_list'] = $this->admin_auth_group_data->lists ();
			foreach($user['auth_group_list'] as $k=>$v){
				$user['auth_group_list'][$k]['name'] = $v['title'];
				unset($user['auth_group_list'][$k]['title']);
			}

			$user['org_list'] = $this->org_list();

			$this->load->view ( '@/add', $user );
		} else {
			exit( '该用户不存在' );
		}
	}

	/**
	 * 获取组织列表
	 */
	public function org_list(){

		$org_list = $this->admin_organization_data->lists ();

		foreach($org_list as $k=>$v){
			$q = '|';
			for ($i=1;$i < $v['level'];$i++){
				$q .= '--';
			}
			$org_list[$k]['name'] = $q.$v['name'];
			unset($org_list[$k]['level']);
		}
		$org_list = array2level($org_list);

		return $org_list;
	}


	/**
	 * 根据组织 查询用户列表
	 */
	public function lists_org_id(){

		$o_id = input('o_id');

		$lists = $this->admin_data->lists_org_id($o_id);

		$ret = ['code'=>0,'msg'=>'ok','data'=>$lists];
		echo json_encode($ret);
	}

}