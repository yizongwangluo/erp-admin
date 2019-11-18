<?php
/**
 * 支付返回事件处理器接口
 * User: xiongbaoshan
 * Date: 2016/4/8
 * Time: 11:18
 */

namespace Application\Component\Contract\Payment;


interface BackEventHandler
{

    public function __invoke($order_number,$platform_order_number,$trading_amount,$trading_time,$buyer_email);

}