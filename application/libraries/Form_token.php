<?php

/**
 * 表单令牌（防刷新）
 * User: xiongbaoshan
 * Date: 2016/03/30
 * Time: 10:20
 */
final class Form_token
{
	protected $token_name = 'form_token';
	static $token_value = null;

	/**
	 * 生成令牌
	 * @return string
	 */
	public function create ()
	{
		if ( !self::$token_value ) {
			self::$token_value = $_SESSION[$this->token_name] = $this->random_token ();
		}
		return (string)self::$token_value;
	}

	/**
	 * 返回令牌名称
	 * @return string
	 */
	public function get_name ()
	{
		return $this->token_name;
	}

	/**
	 * 验证令牌
	 * @return bool
	 */
	public function validate ()
	{
		$response_token = get_instance ()->input->get ( $this->token_name );
		if ( !$response_token ) {
			$response_token = get_instance ()->input->post ( $this->token_name );
		}

		if ( !$response_token ) {
			return false;
		}

		if ( $response_token != $_SESSION[$this->token_name] ) {
			return false;
		}
		unset( $_SESSION[$this->token_name] );

		return true;

	}


	/**
	 * 随机生成令牌
	 * @return int
	 */
	protected function random_token ()
	{
		return rand ( 100000, 999999 );
	}

}