<?php

/**
 * 类库加载器
 * User: xiongbaoshan
 * Date: 2015/11/26
 * Time: 11:06
 */
class MY_Loader extends CI_Loader
{


	public function database ( $params = '', $return = FALSE, $query_builder = NULL )
	{

		if ( ENVIRONMENT != 'production' ) {
			//只允许在指定层访问数据库
			$caller = debug_backtrace ( DEBUG_BACKTRACE_IGNORE_ARGS, 2 )[1];
			if ( isset( $caller['class'] ) ) {
				$allow_call_layers = array (
					'Application\Component\Common\IData',//数据层
					'Transaction',//事务
					'Test',//测试文件
					'Application\Component\Libs\Auth',//权限认证
					'Application\Component\Concrete\Payment\OrderFacade',//支付系统
				);
				//约束只允许在数据层和报表层访问数据库
				$is_allow_call = FALSE;
				foreach ( $allow_call_layers as $layer ) {
					if ( is_subclass_of ( $caller['class'], $layer ) || $caller['class'] == $layer ) {
						$is_allow_call = TRUE;
					}
				}
				if ( !$is_allow_call ) {
					throw new Exception( 'deny call database in method: ' . $caller['class'] . '::' . $caller['function'] );
				}
				static $db_handle = NULL;
				if ( $db_handle ) {
					return $db_handle;
				} else {
					return $db_handle = parent::database ( $params, $return, $query_builder );
				}

			} else {
				throw new Exception( 'deny call database in function: ' . $caller['function'] );
			}

		} else {

			return parent::database ( $params, $return, $query_builder );
		}


	}

	/**
	 * 更好的视图渲染函数
	 * @param $view
	 * @param array $vars
	 * @param bool|FALSE $return
	 * @return string
	 */
	public function view ( $view = '', $vars = array (), $return = FALSE )
	{

		//todo:默认模板路径
		$router = get_instance ()->router;
		$full_view_path = strtolower (
			($router->fetch_directory () ? $router->fetch_directory () . '/' : '')
			. $router->fetch_class () . '/'
			. $router->fetch_method ()
		);

		//todo:自动识别模板
		if ( !$view ) $view = $full_view_path;

		//todo:相对当前模板目录
		if ( $view[0] == '@' ) $view = str_replace ( "@/", dirname ( $full_view_path ) . '/', $view );


		//todo:修复CI模板引擎变量污染BUG
		static $load_counter = 0;
		static $common_vars = array ();
		$load_counter++;
		if ( $load_counter === 1 ) {
			$common_vars = array_merge ( $this->get_vars (), $vars );
			define ( '__VIEW__', $view );
		}
		$this->clear_vars ();
		$vars = array_merge ( $common_vars, $vars );

		return parent::view ( $view, $vars, $return );
	}

	/**
	 * 检测模板是否存在
	 * @param string $view
	 * @return bool
	 */
	public function view_exists ( $view = '' )
	{
		return file_exists ( APPPATH . 'views/' . $view . '.php' );
	}


	/**
	 * 清除所有模板变量
	 * @return $this
	 */
	public function clear_vars ()
	{
		$this->_ci_cached_vars = array ();
		return $this;
	}

	/**
	 * 返回所有模板变量
	 * @return array
	 */
	public function get_vars ()
	{
		return $this->_ci_cached_vars;
	}
}