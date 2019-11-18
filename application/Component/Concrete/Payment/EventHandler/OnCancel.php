<?php
/**支付取消
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/6/8 0008
 * Time: 18:00
 */
namespace Application\Component\Concrete\Payment\EventHandler;
use Application\Component\Concrete\Payment\OrderFacade;
use Application\Component\Contract\Payment\CancelEventHandler;

class OnCancel implements CancelEventHandler {
	public function __invoke ($order_number,$trade_no)
	{
		// TODO: Implement __invoke() method.
		$order_facade=new OrderFacade();
		$order=$order_facade->get_order_by_order_number($order_number);
		if(!$order){
			return ;
		}
		if($order_facade->payment_closed($order['status'])){
			return ;
		}
		if($order_facade->order_cancel($order['origin_order_type'],$order['origin_order_id'],$trade_no)){
			return true;
		}
	}
}