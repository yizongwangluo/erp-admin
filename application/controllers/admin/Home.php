<?php

/**
 * 后台首页
 * User: xiongbaoshan
 * Date: 2016/7/13
 * Time: 9:46
 */
class Home extends \Application\Component\Common\AdminSessionValidateController
{

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'data/admin_data' );
	}

	public function index ()
	{
		$this->load->view ( '' );
	}

	public function changeMePassword ()
	{
		if ( IS_POST ) {
			$old_password = input ( 'old_password' );
			$new_password = input ( 'new_password' );
			$real_name = input ( 'real_name' );
			if ( $new_password == $old_password ) {
				$this->output->ajax_return ( AJAX_RETURN_FAIL, '新密码不能与原密码相同哦！' );
			}
			$old_password_md5 = $this->admin_data->encrypt_password ( $old_password );
			if ( $this->admin['user_password'] != $old_password_md5 ) {
				$this->output->ajax_return ( AJAX_RETURN_FAIL, '原密码不正确' );
			}
			if ( !$this->admin_data->update ( $this->admin['id'], [
				'user_password' => $this->admin_data->encrypt_password ( $new_password ),
				'real_name' => $real_name
			] )
			) {
				$this->output->ajax_return ( AJAX_RETURN_FAIL, '后台管理员信息更新失败' );
			}
			$this->output->ajax_return ( AJAX_RETURN_SUCCESS, 'ok' );

		} else {
			$view['user_info'] = $this->admin;
			$this->load->view ( '', $view );
		}
	}

}