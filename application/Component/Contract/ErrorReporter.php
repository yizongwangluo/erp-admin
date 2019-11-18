<?php
/**
 * 错误报告者接口
 * User: xiongbaoshan
 * Date: 2016/4/1
 * Time: 17:37
 */

namespace Application\Component\Contract;


interface ErrorReporter
{

    /**
     * 获取错误信息
     * @return mixed
     */
    public function get_error();

}