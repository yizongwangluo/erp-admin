<?php
/**
 * 支付取消事件
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/8 0008
 * Time: 18:07
 */
namespace Application\Component\Contract\Payment;
interface CancelEventHandler{
	public function __invoke ($order_number,$trade_no);
}