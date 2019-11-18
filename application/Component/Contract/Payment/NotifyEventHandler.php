<?php
/**
 * 付款通知事件回调接口
 * User: xiongbaoshan
 * Date: 2016/4/6
 * Time: 16:39
 */

namespace Application\Component\Contract\Payment;


interface NotifyEventHandler
{
    /**
     * 执行回调
     * @param $order_number 网站订单号
     * @param $platform_order_number 支付平台流水号
     * @param $trading_amount 交易成功金额
     * @param $trading_time 交易成功时间
     * @param $buyer_account 交易账号
     * @return mixed
     */
    public function __invoke($order_number,$platform_order_number,$trading_amount,$trading_time,$buyer_account);

}