<?php
/**
 * 支付接口规范
 * User: xiongbaoshan
 * Date: 2016/4/6
 * Time: 15:36
 */

namespace Application\Component\Contract\Payment;


interface Payment
{
    /**
     * 提交付款请求
     * @param $total_fee 付款金额
     * @param $order_number 订单号码
     * @param $order_name 订单名称
     * @param $order_description 订单描述信息
     * @return string
     */
    public function submit($total_fee,$order_number,$order_name,$order_description);

    /**
     * 接收支付通知
     * @param NotifyEventHandler $notify_event_handler
     * @return void
     */
    public function notify(NotifyEventHandler $notify_event_handler);


    /**
     * 处理支付返回
     * @param BackEventHandler $back_event_handler
     * @return mixed
     */
    public function back(BackEventHandler $back_event_handler);

}