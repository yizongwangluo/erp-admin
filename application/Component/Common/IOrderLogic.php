<?php
/**
 * 订单逻辑接口
 * User: xiongbaoshan
 * Date: 2016/8/7
 * Time: 18:56
 */

namespace Application\Component\Common;


use Application\Component\Contract\ErrorReporter;

interface IOrderLogic extends ErrorReporter
{
    /**
     * 付款成功通知时执行（一般做一些发货扣库存等操作）
     * @param $order_id
     * @return void
     */
    public function on_payment_notify($order_id);

    /**
     * 订单付款完成后执行（一般显示付款成功页面）
     * @param $order_id
     * @return void
     */
    public function on_payment_complete($order_id);

}