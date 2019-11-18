<?php
/**
 * 默认事件处理器
 * User: xiongbaoshan
 * Date: 2016/4/7
 * Time: 14:29
 */

namespace Application\Component\Concrete\Payment\EventHandler;


use Application\Component\Concrete\Payment\OrderFacade;
use Application\Component\Contract\Payment\NotifyEventHandler;
use Application\Component\Contract\Payment\SuccessEventHandler;

class OnNotify implements NotifyEventHandler
{
    public function __invoke($order_number, $platform_order_number, $trading_amount, $trading_time,$buyer_account, array $extends = [])
    {
        $order_facade=new OrderFacade();
        $order=$order_facade->get_order_by_order_number($order_number);
        if(!$order){
            return;
        }
        if($order_facade->payment_succeeded($order['status'])){
            return;
        }
        //回调支付成功处理程序
        $order_facade->payment_success($order['order_id'],$platform_order_number,$trading_time,$buyer_account);

        get_instance()->load->model('facade/order_facade');

        get_instance()->order_facade->on_payment_notify($order['origin_order_type'],$order['origin_order_id'],$extends);

        //支付事务完成
        $order_facade->payment_complete($order['order_id'],time());
    }
}