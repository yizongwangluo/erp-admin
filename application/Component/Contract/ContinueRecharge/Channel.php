<?php
/**
 * 续充渠道商接口
 * User: xiongbaoshan
 * Date: 2016/8/8
 * Time: 18:11
 */

namespace Application\Component\Contract\ContinueRecharge;


use Application\Component\Contract\ErrorReporter;

interface Channel extends ErrorReporter
{
    /**
     * 发货接口
     * @param $order
     * @return bool
     */
    public function delivery(array $order);

}