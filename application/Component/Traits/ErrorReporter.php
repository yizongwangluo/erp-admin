<?php
/**
 * 错误报告者
 * User: xiongbaoshan
 * Date: 2016/4/1
 * Time: 17:30
 */

namespace Application\Component\Traits;


trait ErrorReporter
{
    private $error_msg='';//错误消息


    /**
     * 获取错误信息
     * @return string
     */
    public function get_error(){
        return $this->error_msg;
    }

    /**
     * 抛出错误信息
     * @param string $msg
     * @return bool
     */
    protected function set_error($msg=''){
        $this->error_msg=$msg;
        return true;
    }

}