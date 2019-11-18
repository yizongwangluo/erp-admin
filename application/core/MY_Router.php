<?php

/**
 * 扩展路由器
 * User: xiongbaoshan
 * Date: 2016/1/19
 * Time: 17:13
 */
class MY_Router extends CI_Router
{
	protected $url_create_engines = array (
		'standard_url',//标准URL
	);

	/**
	 * 生成URL
	 * @param string $uri 控制器/方法/{参数1}/{参数N}...(注：不带{}表示该参数在URL中不可变,反之你懂得)
	 * @param string $params GET参数,也就是?号后面的部分（可以是字符串a=b&c=d或者key=>val数组）
	 * @param string $anchor 锚点
	 * @return string
	 */
	public function create_url ( $uri = '', $params = '', $anchor = '' )
	{
		$CI =& get_instance ();
		$uri = trim ( $uri );
		//优先路由缓存中查找
		$cache_key = 'route_' . $_SERVER['HTTP_HOST'] . IS_WAP . $uri;
		if ( !($url = $CI->cache->redis->get ( $cache_key )) ) {
			//动态分析路由表配置
			static $routes_map = null;
			if ( !$routes_map ) {
				$routes_map = array_flip ( $this->routes );
			}
			foreach ( $this->url_create_engines as $engine_name ) {
				$method = "_create_url_for_{$engine_name}";
				if ( $url = $this->{$method}( $uri, $routes_map ) ) {
					$CI->cache->redis->save ( $cache_key, $url, 60 * 60 * 5 );
					break;
				}
			}
		}
		if ( !$url ) {
			return false;
		}

		if ( $params ) {
			if ( is_array ( $params ) ) {
				$params = http_build_query ( $params );
			}
			$url .= '?' . $params;
		}

		if ( $anchor ) {
			$url .= '#' . $anchor;
		}
		//结尾不加斜杠的URL
		$not_check = array (
			'help/info',
			'?'
		);
		str_replace ( $not_check, '', $url, $countNumber );

        //如果是移动端，则去掉mobile/
		if($_SERVER['HTTP_HOST'] == 'xxm.jiaoyitu.com'){
            $url=str_replace(['mobile/'], [''], $url);
        }

		if ( !empty( $countNumber ) ) {
			return trim ( $url );
		} else {
			return trim ( $url ) . '/';
		}

	}

	protected function _create_url_for_standard_url ( $uri, $routes_map = array () )
	{
		$uri_params = array ();
		$i = 0;
		$route_to = preg_replace_callback ( '/\{(.*?)\}/', function ( $v ) use ( &$uri_params, &$i ) {
			$v = $v[1];
			if ( !$v ) {
				return '';
			}
			++$i;
			$uri_params[$i] = $v;
			return "$" . ($i);
		}, $uri );
		$i = 0;
		$rewrite_from = isset( $routes_map[$route_to] ) ? $routes_map[$route_to] : null;
		if ( $rewrite_from ) {
			$url = preg_replace_callback ( '/\(.*?\)/', function ( $v ) use ( &$uri_params, &$i ) {
				++$i;
				if ( isset( $uri_params[$i] ) ) {
					return $uri_params[$i];
				} else {
					return $v[0];
				}
			}, $rewrite_from );
			$url = str_replace ( array ('(', ')', '\\'), '', $url );
			return base_url ( $url );
		} else {
			return base_url ( str_replace ( ['{', '}'], '', $uri ) ) . $this->config->item ( 'url_suffix' );
		}
	}

	/**
	 * 设置默认控制器（todo:修复CI默认控制器不支持目录的BUG）
	 */
	protected function _set_default_controller ()
	{
		if ( empty( $this->default_controller ) ) {
			show_error ( 'Unable to determine what should be displayed. A default route has not been specified in the routing file.' );
		}

		// Is the method being specified?
		$tmp_info = explode ( '/', $this->default_controller );
		if ( count ( $tmp_info ) > 1 ) {
			$this->directory = dirname ( $this->default_controller ) . '/';
			$class = basename ( $this->default_controller );
		} else {
			$class = $this->default_controller;
		}
		$method = 'index';

		if ( !file_exists ( APPPATH . 'controllers/' . $this->directory . ucfirst ( $class ) . '.php' ) ) {
			// This will trigger 404 later
			return;
		}

		$this->set_class ( $class );
		$this->set_method ( $method );

		// Assign routed segments, index starting from 1
		$this->uri->rsegments = array (
			1 => $class,
			2 => $method
		);

		log_message ( 'debug', 'No URI present. Default controller set.' );
	}

}