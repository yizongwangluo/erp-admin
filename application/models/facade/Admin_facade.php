<?php

/**
 * 后台业务模块
 * User: xiongbaoshan
 * Date: 2016/7/21
 * Time: 14:59
 */
class Admin_facade extends \Application\Component\Common\IFacade
{
	const SESSION_USER_FLAG = 'jyt_admin_id_2017';

	function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/admin_data' );

	}

	/**
	 * 管理员登陆
	 * @param $user_name
	 * @param $user_password
	 * @return bool
	 */
	public function login ( $user_name, $user_password )
	{

		$admin = $this->admin_data->get_info_by_user_name ( $user_name );
		if ( !$admin ) {
			$this->set_error ( '不存在此用户！' );
			return false;
		}

		if ( !$this->admin_data->compare_password ( $user_password, $admin['user_password'] ) ) {
			$this->set_error ( '账号或者密码错误' );
			return false;
		}

		if ( $admin['is_disable'] ) {
			$this->set_error ( '该账号已被禁用' );
			return false;
		}

		if ( !$this->admin_data->update ( $admin['id'], [
			'login_time' => time (),
			'login_ip' => $this->input->ip_address (),
		] )
		) {
			$this->set_error ( '记录登陆信息失败' );
			return false;
		}
		$this->session->set_userdata ( self::SESSION_USER_FLAG, $admin['id'] );
		set_cookie ( self::SESSION_USER_FLAG, auth_code ( $admin['id'], 'ENCODE' ), 43200 );
		$this->admin_logs ( '登陆', $admin );
		return true;
	}

	/**
	 * 创建管理员
	 * @param $user_name
	 * @param $user_password
	 * @param $real_name
	 * @param int $role_id
	 * @return bool
	 */
	public function create ( $user_name, $user_password, $real_name = '', $role_id = 0, $is_disable = 0 )
	{

		$admin = $this->admin_data->get_info_by_user_name ( $user_name );
		if ( $admin ) {
			$this->set_error ( '用户名已被使用！' );
			return false;
		}

		if ( !$this->admin_data->store ( [
			'user_name' => $user_name,
			'user_password' => $this->admin_data->encrypt_password ( $user_password ),
			'real_name' => $real_name,
			'role_id' => $role_id,
			'is_disable' => $is_disable,
			'dateline' => time ()
		] )
		) {
			$this->set_error ( '建立账号失败' );
			return false;
		}

		return true;
	}

	/**
	 * 更新管理员信息
	 * @param $userid
	 * @param $user_name
	 * @param $user_password
	 * @param string $real_name
	 * @param int $role_id
	 * @param int $is_disable
	 * @return bool
	 */
	public function update ( $userid, $user_name, $user_password, $real_name = '', $role_id = 0, $is_disable = 0 )
	{
		if ( !is_numeric ( $role_id ) ) {
			$this->set_error ( '权限组必须选择哦' );
			return false;
		}
		$admin = $this->admin_data->get_info_by_user_name ( $user_name );
		if ( $admin['id'] != $userid ) {
			$this->set_error ( '该用户名已被使用！' );
			return false;
		}
		if ( !$this->admin_data->update ( $userid, [
			'user_name' => $user_name,
			'user_password' => $this->admin_data->encrypt_password ( $user_password ),
			'real_name' => $real_name,
			'role_id' => $role_id,
			'is_disable' => $is_disable,
		] )
		) {
			$this->set_error ( '后台管理员信息更新失败' );
			return false;
		}
		return true;
	}

	/**
	 * 管理员退出
	 */
	public function logout ()
	{

		$this->admin_logs ( '退出后台', $this->get_login_user () );
		$this->session->unset_userdata ( self::SESSION_USER_FLAG );
		delete_cookie ( self::SESSION_USER_FLAG );
	}

	/**
	 * 获取登陆用户
	 * @return null
	 */
	public function get_login_user ()
	{

		$login_admin = $this->session->userdata ( self::SESSION_USER_FLAG );
		if ( !$login_admin ) {
			$login_admin = auth_code ( get_cookie ( self::SESSION_USER_FLAG ) );
			if ( empty( $login_admin ) ) {
				return null;
			}
		}

		return $this->admin_data->get_info ( $login_admin );
	}

	/**
	 * 新增日志
	 * @param $title
	 * @param $admin
	 */
	private function admin_logs ( $title, $admin )
	{

		model ( 'data/admin_logs_data' )->store (
			[
				'admin_id' => $admin['id'],
				'username' => $admin['user_name'],
				'title' => $title,
				'content' => \json_encode ( $_REQUEST ),
				'url' => $this->uri->uri_string (),
				'ip' => $this->input->ip_address (),
				'ua' => $_SERVER['HTTP_USER_AGENT'],
				'request' => $_SERVER['REQUEST_METHOD'],
				'dateline' => $_SERVER['REQUEST_TIME']
			]
		);
	}

}