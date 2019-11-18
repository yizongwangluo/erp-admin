<?php

/**
 * 结果输出扩展
 * User: xiongbaoshan
 * Date: 2015/11/11
 * Time: 11:12
 */
class MY_Output extends CI_Output
{
	/**
	 * ajax返回数据
	 * @param int $status
	 * @param string $msg
	 * @param array $data
	 */
	public function ajax_return ( $status = 0, $msg = '', $data = array () )
	{
		if ( $status ) destroy_verify_code ();
		header ( 'content-type:text/json;charset=utf-8' );
		echo json_encode ( array (
			'status' => $status,
			'msg' => $msg,
			'data' => $data
		) );
		exit( 0 );
	}

	/**
	 * 弹出提示框
	 * @param string $msg
	 * @param null $url
	 */
	function alert ( $msg = '', $url = null )
	{
		if ( !$url ) $url = $_SERVER['HTTP_REFERER'];
		echo '<script type="text/javascript">alert("' . $msg . '");location.href="' . $url . '";</script>';
		exit;
	}

	/**
	 * 错误提示模板跳转
	 * @param $msg
	 * @param null $url
	 * @param string $time
	 */
	public function error ( $msg, $url = null, $time = '3' )
	{
		if ( !$url ) $url = $_SERVER['HTTP_REFERER'];
		$data['code'] = '0';
		$data['msg'] = $msg;
		$data['url'] = $url;
		$data['time'] = $time;
		if ( class_exists ( 'CI_Controller', FALSE ) ) {
			$CI =& get_instance ();
		}
		$CI->load->view ( 'common/jump', $data );
	}

	public function success ( $msg, $url = null, $time = '3' )
	{
		if ( !$url ) $url = $_SERVER['HTTP_REFERER'];
		$data['code'] = '1';
		$data['msg'] = $msg;
		$data['url'] = $url;
		$data['time'] = $time;
		if ( class_exists ( 'CI_Controller', FALSE ) ) {
			$CI =& get_instance ();
		}
		$CI->load->view ( 'common/jump', $data );
	}

}