<?php
/**
 * 后台会话认证控制器
 * User: xiongbaoshan
 * Date: 2016/7/21
 * Time: 16:02
 */

namespace Application\Component\Common;

use Application\Component\Libs\Auth;

class AdminSessionValidateController extends \MY_Controller
{

	protected $admin = null;

	public function __construct ()
	{
		parent::__construct ();
		$this->session_validate ();
		$this->menulist ();
	}

	public function menulist ()
	{
		$admin_id = $this->admin['id'];
		$redis_key = 'admin_menulist_' . $admin_id;
		if ( !($menu = $this->cache->get ( $redis_key )) ) {
			$menu = [];
			$menuList = model ( 'facade/menu_facade' )->getMenuList ( 'status=1', 'asc' );
			if ( $this->admin['role_id'] != 1 ) {
				$auth = new Auth();
				foreach ( $menuList as $value ) {
					if ( $auth->check ( $value['id'], $admin_id, 1, 'number' ) ) {
						$menu[] = $value;
					}
				}
				$menu = list_to_tree ( $menu );
			} else {
				$menu = list_to_tree ( $menuList );
			}
			$this->cache->save ( $redis_key, $menu );
		}
		$this->load->vars ( 'menulist', $menu );
	}

	public function session_validate ()
	{
		$this->load->model ( 'facade/admin_facade' );
		$login_user = $this->admin_facade->get_login_user ();

		if ( !$login_user ) {
			redirect ( 'admin/login' );
		}

		if ( $login_user['is_disable'] ) {
			$this->output->error ( '该账号已被禁1用！', base_url ( 'admin/login/logout' ) );
		}

		$this->admin = $login_user;
		$this->load->vars ( 'admin', $login_user );


		$directory = $this->router->directory;
		$class = $this->router->class;
		$method = $this->router->method;
		$nowUrl = $directory . $class . '/' . $method;
		// 排除权限
		$not_check = ['admin', 'admin/Home/index', 'admin/authgroup/getjson'];
		if ( !in_array ( $nowUrl, $not_check )  ) {
			$auth = new Auth();
			if ( !$auth->check ( $nowUrl, $login_user['id']) && $this->admin['role_id']!='1') {
				$err = 'Sorry，你没有被管理员授权操作该栏目';
				if ( $this->input->is_ajax_request () ) {
					$this->output->ajax_return ( AJAX_RETURN_FAIL, $err );
				} else {
					$this->output->error ( $err );
				}
			}

		}
		$this->add_admin_logs ();

	}

	protected function add_admin_logs ( $title = '', $admin = '' )
	{
		if ( empty( $admin ) ) {
			$admin = $this->admin;
		}
		$url = $this->uri->uri_string ();
		if ( empty( $title ) ) {
			$result = model ( 'data/menu_data' )->find ( array ('url' => $url) );
			$title = $result['name'];
		}
		model ( 'data/admin_logs_data' )->store (
			[
				'admin_id' => $admin['id'],
				'username' => $admin['user_name'],
				'title' => $title,
				'content' => \json_encode ( $_REQUEST ),
				'url' => $url,
				'ip' => $this->input->ip_address (),
				'ua' => $_SERVER['HTTP_USER_AGENT'],
				'request' => $_SERVER['REQUEST_METHOD'],
				'dateline' => $_SERVER['REQUEST_TIME']
			]
		);
	}
}