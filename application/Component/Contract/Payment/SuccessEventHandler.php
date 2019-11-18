<?php
/**
 * 支付成功事件处理器接口
 * User: xiongbaoshan
 * Date: 2016/4/8
 * Time: 19:14
 */

namespace Application\Component\Contract\Payment;


interface SuccessEventHandler
{
    /**
     * @param $origin_order_id 原始订单ID
     * @return mixed
     */
    public function __invoke($origin_order_id);

}