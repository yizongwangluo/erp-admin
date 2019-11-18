<?php

/**
 * 配置加载器扩展
 * User: xiongbaoshan
 * Date: 2015/11/16
 * Time: 14:32
 */
class MY_Config extends CI_Config
{
	/**
	 * 返回整个配置文件数组
	 * @param string $config_file_index
	 * @return array
	 */
	public function list_item ( $config_file_index = '' )
	{
		if ( !$config_file_index ) {
			return $this->config;
		}
		if ( !isset( $this->config[$config_file_index] ) ) {
			show_error ( 'config file ' . $config_file_index . ' not loaded' );
		}
		return $this->config[$config_file_index];
	}
}