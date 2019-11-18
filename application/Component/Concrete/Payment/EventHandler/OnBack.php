<?php
/**
 * 支付成功返回事件处理器
 * User: xiongbaoshan
 * Date: 2016/4/8
 * Time: 11:32
 */

namespace Application\Component\Concrete\Payment\EventHandler;


use Application\Component\Concrete\Payment\OrderFacade;
use Application\Component\Contract\Payment\BackEventHandler;

class OnBack implements BackEventHandler
{
	const PAYMENT_GATEWAY_REFER_URL_KEY = 'payment_gateway_refer_url_key';

	public function __invoke ( $order_number, $platform_order_number, $trading_amount, $trading_time,$buyer_email, array $extends = [] )
	{
		$order_facade = new OrderFacade();
		$order = $order_facade->get_order_by_order_number ( $order_number );
		if ( !$order ) {
			die('不存在此订单！');
		}
		if (!$order_facade->payment_succeeded ( $order['status'] )) {
			//回调支付成功处理程序
			$order_facade->payment_success ( $order['order_id'], $platform_order_number, $trading_time, $buyer_email );
			get_instance ()->load->model ( 'facade/order_facade' );
			get_instance ()->order_facade->on_payment_notify ( $order['origin_order_type'], $order['origin_order_id'], $extends );
			//支付事务完成
			$order_facade->payment_complete ( $order['order_id'], time () );
		}
		$dump = get_cookie ( self::PAYMENT_GATEWAY_REFER_URL_KEY );
		empty($dump) ? $dump ='' : delete_cookie ( self::PAYMENT_GATEWAY_REFER_URL_KEY );
		redirect ( $dump . 'order/complete/' . $order['origin_order_id'] );

	}

}