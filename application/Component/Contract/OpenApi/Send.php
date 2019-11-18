<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/7/17 0017
 * Time: 14:16
 */

namespace Application\Component\Contract\OpenApi;
interface  Send
{
	public function fire ( $info, \Closure $callback );
}