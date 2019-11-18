<?php
/**
 * 首充计价规则接口
 * User: xiongbaoshan
 * Date: 2016/8/22
 * Time: 17:16
 */

namespace Application\Component\Contract\ContinueRecharge;


use Application\Component\Contract\ErrorReporter;

interface PriceRule extends ErrorReporter
{
    /**
     * 设置配置
     * @param $config
     * @return void
     */
    public function set_config($config);

    /**
     * 计算总额
     * @param $goods_id
     * @param $goods_price
     * @param $buy_quantity
     * @return float
     */
    public function calculate($goods_id,$goods_price,$buy_quantity);
}