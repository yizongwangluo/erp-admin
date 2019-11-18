<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/8 0008
 * Time: 16:10
 */
/**
 * 模型自动加载
 */
if (!function_exists ('model')){
    //自动加载model
	function model ( $model )
	{
		static $obj_cache = [];
		if ( empty( $obj_cache[$model] ) ) {
			$CI = &get_instance ();
			$CI->load->model ( $model );
			$i = explode ( '/', $model );
			$dd = array_pop ($i);
			$obj_cache[$model] = $CI->{$dd};
		}
		return $obj_cache[$model];
	}

}
/**
 *
 */
if (!function_exists ('input')){
	/**
	 * 获取输入数据 支持默认值和过滤
	 * @param string    $key 获取的变量名
	 * @param mixed     $default 默认值
	 * @param string    $filter 过滤方法
	 * @return mixed
	 */
	function input($key = '', $default = null, $filter = '')
	{
		static $_PUT	=	null;
		if (0 === strpos($key, '?')) {
			$key = substr($key, 1);
			$has = true;
		}
		if ($pos = strpos($key, '.')) {
			// 指定参数来源
			list($method, $key) = explode('.', $key, 2);
			if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete', 'param', 'request', 'session', 'cookie', 'server', 'env', 'path', 'file'])) {
				$key    = $method . '.' . $key;
				$method = 'param';
			}
		} else {
			// 默认为自动判断
			$method = 'param';
		}
		switch(strtolower($method)) {
			case 'get'     :
				$input =& $_GET;
				break;
			case 'post'    :
				$input =& $_POST;
				break;
			case 'put'     :
				if(is_null($_PUT)){
					parse_str(file_get_contents('php://input'), $_PUT);
				}
				$input 	=	$_PUT;
				break;
			case 'param'   :
				switch($_SERVER['REQUEST_METHOD']) {
					case 'POST':
						$input  =  $_POST;
						break;
					case 'PUT':
						if(is_null($_PUT)){
							parse_str(file_get_contents('php://input'), $_PUT);
						}
						$input 	=	$_PUT;
						break;
					default:
						$input  =  $_GET;
				}
				break;
			case 'path'    :
				$input  =   array();
				if(!empty($_SERVER['PATH_INFO'])){
					$depr   =   C('URL_PATHINFO_DEPR');
					$input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
				}
				break;
			case 'request' :
				$input =& $_REQUEST;
				break;
			case 'session' :
				$input =& $_SESSION;
				break;
			case 'cookie'  :
				$input =& $_COOKIE;
				break;
			case 'server'  :
				$input =& $_SERVER;
				break;
			case 'globals' :
				$input =& $GLOBALS;
				break;
			case 'data'    :
				$input =& $datas;
				break;
			default:
				return null;
		}
		$filters    =   isset($filter)?$filter:'';
		if ($key == ''){
			$data       =   $input;
			if($filters) {
				if(is_string($filters)){
					$filters    =   explode(',',$filters);
				}
				foreach($filters as $filter){
					$data   =   array_map_recursive($filter,$data); // 参数过滤
				}
			}
		}elseif(isset($input[$key])) { // 取值操作
			$data       =   $input[$key];
			if($filters) {
				if(is_string($filters)){
					if(0 === strpos($filters,'/')){
						if(1 !== preg_match($filters,(string)$data)){
							// 支持正则验证
							return   isset($default) ? $default : null;
						}
					}else{
						$filters    =   explode(',',$filters);
					}
				}elseif(is_int($filters)){
					$filters    =   array($filters);
				}

				if(is_array($filters)){
					foreach($filters as $filter){
						if(function_exists($filter)) {
							$data   =   is_array($data) ? array_map_recursive($filter,$data) : $filter($data); // 参数过滤
						}else{
							$data   =   filter_var($data,is_int($filter) ? $filter : filter_id($filter));
							if(false === $data) {
								return   isset($default) ? $default : null;
							}
						}
					}
				}
			}
		}else{ // 变量默认值
			$data       =    isset($default)?$default:null;
		}
		is_array($data) && array_walk_recursive($data,'input_Filter');
		return $data;
	}
}
/**
 * 输出json
 */
if (!function_exists ('echo_json')){
	function echo_json($arr){
		header('Content-type: application/json');
		echo json_encode ( $arr );
	}
}