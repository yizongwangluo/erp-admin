<?php
/**
 * 工具类
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/17 0017
 * Time: 17:08
 */

namespace Application\Component\Concrete\OpenApi;
class Tools
{
	/**
	 * sha1加密签名
	 * @param $order_number
	 * @param $app_id
	 * @param $app_key
	 * @return string
	 */
	public static function encrypt ( $order_number, $app_id, $app_key )
	{
		return \sha1 ( $order_number . $app_id . $app_key );
	}
}