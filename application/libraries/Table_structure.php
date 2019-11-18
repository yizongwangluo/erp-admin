<?php

/**
 * 创建表
 * User: xiongbaoshan
 * Date: 2015/12/21
 * Time: 15:20
 */
final class Table_structure
{
	/**
	 * 枚举值验证
	 * @param string $field
	 * @param string $value
	 * @return bool|mixed
	 */
	public function validate ( $field = '', $value = '' )
	{
		$values = $this->get_values ( $field );
		$key = array_search ( $value, $values );
		if ( $key === FALSE ) {
			return FALSE;
		}

		return $key;
	}

	/**
	 * 根据枚举索引获取值
	 * @param string $field
	 * @param string $key
	 * @return mixed
	 */
	public function get_value ( $field = '', $key = '' )
	{
		$values = $this->get_values ( $field );
		return $values[$key];
	}

	/**
	 * 根据枚举字段获取所有值
	 * @param string $field
	 * @return mixed
	 */
	public function get_values ( $field = '' )
	{
		return $this->get_config ()[$field];
	}

	/**
	 * 获取配置映射表
	 * @return mixed
	 */
	protected function get_config ()
	{
		static $config = NULL;
		if ( $config === NULL ) {
			$CI =& get_instance ();
			$CI->config->load ( 'table_structure', true );
			$config = $CI->config->list_item ( 'table_structure' );
		}
		return $config;
	}

}