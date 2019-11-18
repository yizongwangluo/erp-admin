<?php
/**
 * 打折活动规则接口
 * User: xiongbaoshan
 * Date: 2016/9/23
 * Time: 14:45
 */

namespace Application\Component\Contract\DiscountActivity;


use Application\Component\Contract\ErrorReporter;

interface Rule extends ErrorReporter
{
    /**
     * 打折结果
     * @param $user_id
     * @param $goods_id
     * @param $buy_quantity
     * @param $order_amount
     * @return float
     */
    public function discount($user_id,$goods_id,$buy_quantity,$order_amount);

}