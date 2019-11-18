<?php
/**
 * 微信基础权限控制器
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/25 0025
 * Time: 16:27
 */

namespace Application\Component\Common\Wechat;
class WeChatHomeController extends WeChatBaseController
{
	public $user;

	public function __construct ()
	{
		parent::__construct ();
		$this->load->model ( 'facade/user_facade' );
		//初始化用户状态
		$this->get_user_status ();
	}

	/**
	 * 获取用户登陆状态
	 */
	public function get_user_status ()
	{
		$this->user = $this->user_facade->_get_login_user ();
		if ( !$this->user ) {
			$_SESSION['target_url'] = $this->uri->uri_string ();
			$response = $this->app->oauth->scopes ( ['snsapi_userinfo'] )
				->redirect ();
			$response->send ();
		}
	}

	/**
	 * 错误操作提示
	 * @param $msg
	 */
	public function errorTmp ( $msg )
	{
		$viewData['error'] = $msg;
		$this->load->view ( 'weixin/common/error', $viewData );
	}

}